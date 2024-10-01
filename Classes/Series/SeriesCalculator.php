<?php

namespace System25\T3sports\Series;

use Contrib\Doctrine\Common\Collections\Collection;
use Exception;
use Sys25\RnBase\Database\Connection;
use System25\T3sports\Model\Fixture;
use System25\T3sports\Model\Repository\ClubRepository;
use System25\T3sports\Model\Repository\TeamRepository;
use System25\T3sports\Model\Series;
use System25\T3sports\Model\SeriesResult;
use System25\T3sports\Model\SeriesRule;
use System25\T3sports\Model\Team;
use System25\T3sports\Repository\SeriesRepository;
use System25\T3sports\Repository\SeriesResultRepository;
use System25\T3sports\Repository\SeriesRuleRepository;
use System25\T3sports\Service\MatchService;
use System25\T3sports\Utility\ServiceRegistry;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2024 Rene Nitzsche (rene@system25.de)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Service for accessing team information.
 *
 * @author Rene Nitzsche
 */
class SeriesCalculator
{
    private $matchService;
    private $seriesRepo;
    private $seriesRuleRepo;
    private $seriesResultRepo;
    private $teamRepo;
    private $clubRepo;
    private $ruleProvider;
    /** @var Connection */
    private $dbConnection;

    public function __construct(
        SeriesRuleProvider $ruleProvider,
        ClubRepository $clubRepo,
        SeriesRepository $seriesRepo,
        SeriesResultRepository $seriesResultRepo,
        SeriesRuleRepository $seriesRuleRepo,
        TeamRepository $teamRepo,
        ?MatchService $matchService = null
    ) {
        $this->ruleProvider = $ruleProvider;
        $this->matchService = $matchService ?: ServiceRegistry::getMatchService();
        $this->seriesRepo = $seriesRepo;
        $this->seriesResultRepo = $seriesResultRepo;
        $this->seriesRuleRepo = $seriesRuleRepo;
        $this->teamRepo = $teamRepo;
        $this->clubRepo = $clubRepo;
        $this->dbConnection = Connection::getInstance();
    }

    /**
     * (Re-)Calculate given series.
     *
     * @param int $seriesUid
     */
    public function calculate(int $seriesUid, SeriesCalculationVisitorInterface $visitor)
    {
        $series = $this->seriesRepo->findByUid($seriesUid);
        if (null === $series) {
            throw new Exception(sprintf('Match series with uid %d not found in database', $seriesUid));
        }

        $scopeRules = $this->seriesRuleRepo->findBySeries($series);
        foreach ($scopeRules as $scopeRule) {
            /* @var SeriesRule $scopeRule */
            $scopeRule->setRule(
                $this->ruleProvider->getSeriesRuleByAlias($scopeRule->getProperty('rulealias'))
            );
        }

        $clubs = $this->clubRepo->search(
            ['SERIES_SCOPE_MM.uid_local' => [OP_EQ_INT => $series->getUid()]],
            ['what' => 'CLUB.uid']
        );
        $clubs = $clubs->map(function ($item) { return $item['uid']; })->toArray();
        $ageGroup = $series->getProperty('agegroup');

        if ($visitor) {
            $visitor->seriesLoaded($series, $clubs);
        }

        foreach ($clubs as $clubUid) {
            $club = $this->clubRepo->findByUid($clubUid);
            $seriesBag = new SeriesBag($club);
            $matches = $this->lookupMatches($clubUid, $ageGroup);
            if ($visitor) {
                $visitor->matchesLoaded($matches);
            }

            foreach ($matches as $match) {
                try {
                    if ($this->isRuleSatisfied($match, $scopeRules, $clubUid, $ageGroup)) {
                        $seriesBag->appendToSeries($match);
                    } else {
                        $seriesBag->breakSeries();
                    }
                } catch (TeamNotFoundException $e) {
                    // This is okay
                    continue;
                } finally {
                    if ($visitor) {
                        $visitor->matchProcessed($match);
                    }
                }
            }
            $this->persistResult($seriesBag, $series);
            if ($visitor) {
                $visitor->clubProcessed($club, $seriesBag);
            }
        }

        return;
    }

    private function persistResult(SeriesBag $seriesBag, Series $series): void
    {
        $existingResults = $this->seriesResultRepo->findBySeriesAndClub($series, $seriesBag->getClub());

        $oldBestResults = $existingResults->filter(function (SeriesResult $r) { return $r->isTypeBest(); });

        $results = $seriesBag->getBestSeriesResults($series);
        $newHashes = $results->map(function (SeriesResult $r) { return $r->getUniqueKey(); });
        // wir haben Set an Ergebnissen. Alle Ergebnisse, die wir in den vorhandenen Ergebnissen finden,
        // können erhalten bleiben. Was wir da nicht finden, wird gelöscht.

        // Was hier gefunden wird, muss gelöscht werden
        $missingExistingResults = $oldBestResults->filter(function (SeriesResult $r) use ($newHashes) { return !$newHashes->contains($r->getUniqueKey()); });
        $alreadyExistingResults = $oldBestResults->filter(function (SeriesResult $r) use ($newHashes) { return $newHashes->contains($r->getUniqueKey()); });
        foreach ($alreadyExistingResults as $resultSeries) {
            /* @var SeriesResult $resultSeries */
            $newHashes->removeElement($resultSeries->getUniqueKey());
        }
        // Jetzt sind nur noch nicht gespeicherte Elemente in den newHashes

        foreach ($missingExistingResults as $resultSeries) {
            /* @var SeriesResult $resultSeries */
            $this->seriesResultRepo->clearSeriesResult($resultSeries);
        }

        $missingNewResults = $results->filter(function (SeriesResult $r) use ($newHashes) { return $newHashes->contains($r->getUniqueKey()); });

        // Was muss jetzt noch gespeichert werden?
        foreach ($missingNewResults as $resultSeries) {
            $this->persistOrRemove($resultSeries, null);
        }

        // Das das current
        $existingResult = $existingResults->filter(function (SeriesResult $r) { return $r->isTypeCurrent(); })
            ->first();
        $result = $seriesBag->getCurrentSeriesResult($series);
        $this->persistOrRemove($result, $existingResult ?: null);
    }

    private function persistOrRemove(?SeriesResult $result, ?SeriesResult $existingResult): void
    {
        if (!$result || $result->getQuantity() < 2) {
            $this->clearResult($existingResult);

            return;
        }

        if ($existingResult && $existingResult->getUniqueKey() === $result->getUniqueKey()) {
            // Es hat sich nix verändert.

            return;
        }

        $this->clearResult($existingResult);

        $this->seriesResultRepo->persist($result);
        foreach ($result->getFixtures() as $idx => $match) {
            $this->dbConnection->doInsert('tx_t3sportstats_series_result_mm', [
                'uid_local' => $result->getUid(),
                'uid_foreign' => $match->getUid(),
                'tablenames' => 'tx_cfcleague_games',
                'sorting' => $idx,
                'sorting_foreign' => 0,
            ]);
        }
    }

    private function clearResult($seriesResults): void
    {
        if (!$seriesResults) {
            return;
        }

        if (!($seriesResults instanceof Collection)) {
            $seriesResults = [$seriesResults];
        }

        foreach ($seriesResults as $seriesResult) {
            $this->seriesResultRepo->clearSeriesResult($seriesResult);
        }
    }

    /**
     * @param Fixture $match
     * @param Collection<SeriesRule> $rules
     * @param mixed $clubUid
     * @param mixed $ageGroupUid
     * @return bool
     */
    private function isRuleSatisfied(Fixture $match, Collection $rules, $clubUid, $ageGroupUid): bool
    {
        // Team ermitteln
        $isHome = $this->isTeamHome($match, $clubUid, $ageGroupUid);

        $isSatified = true;
        foreach ($rules as $rule) {
            /** @var SeriesRule $rule */
            if (!$rule->isSatisfied($match, $isHome)) {
                return false;
            }
        }

        return $isSatified;
    }

    private function isTeamHome($match, $clubUid, $ageGroupUid): bool
    {
        /** @var Team $team */
        $team = $this->teamRepo->findByUid($match->getProperty('home'));
        if ($team->getProperty('club') === $clubUid
            && ($team->getGroupUid() === $ageGroupUid || 0 === $team->getGroupUid())) {
            return true;
        }
        /** @var Team $team */
        $team = $this->teamRepo->findByUid($match->getProperty('guest'));
        if ($team->getProperty('club') === $clubUid
            && ($team->getGroupUid() === $ageGroupUid || 0 === $team->getGroupUid())) {
            return false;
        }

        throw new TeamNotFoundException(sprintf('Team for club %d not found in match %d', $clubUid, $match->getUid()));
    }

    private function lookupMatches($clubUid, $ageGroupUid): Collection
    {
        $builder = $this->matchService->getMatchTableBuilder();
        $builder->setStatus(Fixture::MATCH_STATUS_FINISHED);
        $builder->setAgeGroups($ageGroupUid);
        $builder->setClubs(''.$clubUid);
        $builder->setOrderByDate(false);
        $builder->setCompetitionObligation(1);

        $fields = $options = [];
        $builder->getFields($fields, $options);
        /** @var \Sys25\RnBase\Domain\Collection\BaseCollection $matches */
        $matches = $this->matchService->search($fields, $options);

        return $matches;
    }
}
