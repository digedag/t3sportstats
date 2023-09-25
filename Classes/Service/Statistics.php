<?php

namespace System25\T3sports\Service;

use Sys25\RnBase\Database\Connection;
use Sys25\RnBase\Search\SearchBase;
use Sys25\RnBase\Utility\Dates;
use Sys25\RnBase\Utility\Logger;
use Sys25\RnBase\Utility\Strings;
use System25\T3sports\Model\Competition;
use System25\T3sports\Model\Fixture;
use System25\T3sports\Model\Team;
use System25\T3sports\Search\SearchCoachStats;
use System25\T3sports\Search\SearchPlayerStats;
use System25\T3sports\Search\SearchRefereeStats;
use System25\T3sports\StatsIndexer\CoachStatsInterface;
use System25\T3sports\StatsIndexer\PlayerStatsInterface;
use System25\T3sports\StatsIndexer\RefereeStatsInterface;
use System25\T3sports\Utility\ServiceRegistry;
use System25\T3sports\Utility\StatsDataBag;
use System25\T3sports\Utility\StatsMatchNoteProvider;
use tx_rnbase;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2022 Rene Nitzsche (rene@system25.de)
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
class Statistics
{
    private $matchService;
    private $indexerProvider;

    public function __construct(MatchService $matchService = null, StatsIndexerProvider $indexerProvider = null)
    {
        $this->matchService = $matchService ?: ServiceRegistry::getMatchService();
        $this->indexerProvider = $indexerProvider ?: StatsIndexerProvider::getInstance();
    }

    /**
     * Update statistics for a competition.
     *
     * @param Competition $competition
     */
    public function indexPlayerStatsByCompetition(Competition $competition)
    {
        // Der Service lädt alle DatenServices für Spielerdaten
        // Danach lädt er die Spiele eines Wettbewerbs
        // Für jedes Spiel werden die Events geladen
        // Anschließend bekommt jeder Service das Spiel, den Spieler und die Events übergeben
        // In ein Datenarray legt er die relevanten Daten für den Spieler
        $builder = $this->matchService->getMatchTableBuilder();
        $builder->setCompetitions($competition->getUid());
        $builder->setStatus(2);
        $fields = $options = [];
        $builder->getFields($fields, $options);
        $matches = $this->matchService->search($fields, $options);
        $this->indexStatsByMatches($matches);
    }

    public function indexStatsByMatches($matches)
    {
        Logger::info('Start player statistics run for '.count($matches).' matches.', 't3sportstats');
        $time = microtime(true);
        $memStart = memory_get_usage();

        // Über alle Spiele iterieren und die Spieler an die Services geben
        for ($j = 0, $mc = count($matches); $j < $mc; ++$j) {
            $matchNotes = ServiceRegistry::getMatchService()->retrieveMatchNotes($matches[$j], true);
            $mnProv = StatsMatchNoteProvider::createInstance($matchNotes);
            // handle Hometeam
            $this->indexPlayerData($matches[$j], $mnProv, true);
            $this->indexCoachData($matches[$j], $mnProv, true);
            $this->indexRefereeData($matches[$j], $mnProv, true);
            // handle Guestteam
            $this->indexPlayerData($matches[$j], $mnProv, false);
            $this->indexCoachData($matches[$j], $mnProv, false);
            $this->indexRefereeData($matches[$j], $mnProv, false);
        }
        if (Logger::isInfoEnabled()) {
            $memEnd = memory_get_usage();
            Logger::info('Player statistics finished.', 't3sportstats', [
                'Execution Time' => (microtime(true) - $time),
                'Matches' => count($matches),
                'Memory Start' => $memStart,
                'Memory End' => $memEnd,
                'Memory Consumed' => ($memEnd - $memStart),
            ]);
        }
    }

    /**
     * Indizierung der Daten und Speicherung in der DB.
     *
     * @param Fixture $match
     * @param StatsMatchNoteProvider $mnProv
     * @param bool $homeTeam
     */
    private function indexPlayerData(Fixture $match, $mnProv, $homeTeam)
    {
        // Services laden
        $servicesArr = $this->lookupPlayerServices();

        $del = $this->clearPlayerData($match, $homeTeam);
        Logger::debug('Player statistics: '.$del.' old records deleted.', 't3sportstats');

        $dataBags = $this->getPlayerBags($match, $homeTeam);
        for ($i = 0, $servicesArrCnt = count($servicesArr); $i < $servicesArrCnt; ++$i) {
            $service = &$servicesArr[$i];
            foreach ($dataBags as $dataBag) {
                $service->indexPlayerStats($dataBag, $match, $mnProv, $homeTeam);
            }
        }
        // Jetzt die Daten wegspeichern
        $this->savePlayerData($dataBags);
        unset($dataBags);
    }

    /**
     * Indizierung der Daten und Speicherung in der DB.
     *
     * @param Fixture $match
     * @param StatsMatchNoteProvider $mnProv
     * @param bool $homeTeam
     */
    private function indexCoachData(Fixture $match, $mnProv, $homeTeam)
    {
        // Services laden
        $servicesArr = $this->lookupCoachServices();

        $del = $this->clearCoachData($match, $homeTeam);
        Logger::debug('Coach statistics: '.$del.' old records deleted.', 't3sportstats');

        $dataBags = $this->getCoachBags($match, $homeTeam);
        for ($i = 0, $servicesArrCnt = count($servicesArr); $i < $servicesArrCnt; ++$i) {
            $service = &$servicesArr[$i];
            foreach ($dataBags as $dataBag) {
                $service->indexCoachStats($dataBag, $match, $mnProv, $homeTeam);
            }
        }
        // Jetzt die Daten wegspeichern
        $this->saveCoachData($dataBags);
        unset($dataBags);
    }

    /**
     * Indizierung der Schiedsrichter-Daten und Speicherung in der DB.
     *
     * @param Fixture $match
     * @param StatsMatchNoteProvider $mnProv
     * @param bool $homeTeam
     */
    private function indexRefereeData(Fixture $match, $mnProv, $homeTeam)
    {
        // Services laden
        $servicesArr = $this->lookupRefereeServices();
        $del = $this->clearRefereeData($match, $homeTeam);
        Logger::debug('Referee statistics: '.$del.' old records deleted.', 't3sportstats');

        $dataBags = $this->getRefereeBags($match, $homeTeam);
        for ($i = 0, $servicesArrCnt = count($servicesArr); $i < $servicesArrCnt; ++$i) {
            $service = &$servicesArr[$i];
            foreach ($dataBags as $dataBag) {
                $service->indexRefereeStats($dataBag, $match, $mnProv, $homeTeam);
            }
        }
        // Jetzt die Daten wegspeichern
        $this->saveRefereeData($dataBags);
        unset($dataBags);
    }

    /**
     * Delete all player data in database for a match.
     *
     * @param Fixture $match
     */
    private function clearPlayerData(Fixture $match, $isHome)
    {
        $where = 't3match = '.$match->getUid().' AND ishome='.($isHome ? 1 : 0);

        return Connection::getInstance()->doDelete('tx_t3sportstats_players', $where);
    }

    /**
     * Delete all coach data in database for a match.
     *
     * @param Fixture $match
     */
    private function clearCoachData(Fixture $match, $isHome)
    {
        $where = 't3match = '.$match->getUid().' AND ishome='.($isHome ? 1 : 0);

        return Connection::getInstance()->doDelete('tx_t3sportstats_coachs', $where);
    }

    /**
     * Delete all referee data in database for a match.
     *
     * @param Fixture $match
     */
    private function clearRefereeData(Fixture $match, $isHome)
    {
        $where = 't3match = '.$match->getUid().' AND ishome='.($isHome ? 1 : 0);

        return Connection::getInstance()->doDelete('tx_t3sportstats_referees', $where);
    }

    private function savePlayerData($dataBags)
    {
        $now = Dates::datetime_tstamp2mysql(time());
        foreach ($dataBags as $dataBag) {
            $data = $dataBag->getTypeValues();
            $data['crdate'] = $now;
            Connection::getInstance()->doInsert('tx_t3sportstats_players', $data);
        }
    }

    private function saveCoachData($dataBags)
    {
        $now = Dates::datetime_tstamp2mysql(time());
        foreach ($dataBags as $dataBag) {
            $data = $dataBag->getTypeValues();
            $data['crdate'] = $now;
            Connection::getInstance()->doInsert('tx_t3sportstats_coachs', $data);
        }
    }

    private function saveRefereeData($dataBags)
    {
        $now = Dates::datetime_tstamp2mysql(time());
        foreach ($dataBags as $dataBag) {
            $data = $dataBag->getTypeValues();
            $data['crdate'] = $now;
            Connection::getInstance()->doInsert('tx_t3sportstats_referees', $data);
        }
    }

    /**
     * Liefert die DataBags für die Spieler eines beteiligten Teams.
     *
     * @param Fixture $match
     * @param bool $home true, wenn das Heimteam geholt werden soll
     *
     * @return StatsDataBag[]
     */
    public function getPlayerBags($match, $home)
    {
        $type = $home ? 'home' : 'guest';
        $ids = $match->getProperty('players_'.$type);
        if (strlen($match->getProperty('substitutes_'.$type)) > 0) {
            // Auch Ersatzspieler anhängen
            if (strlen($ids) > 0) {
                $ids .= ','.$match->getProperty('substitutes_'.$type);
            } else {
                $ids = $match->getProperty('substitutes_'.$type);
            }
        }
        $bags = [];
        $playerIds = Strings::intExplode(',', $ids);
        foreach ($playerIds as $uid) {
            if ($uid <= 0) {
                continue; // skip dummy records
            }
            $bag = $this->createProfileBag($uid, $match, $home, 'player');
            $bags[] = $bag;
        }

        return $bags;
    }

    /**
     * Liefert die DataBags für die Trainer eines beteiligten Teams.
     *
     * @param Fixture $match
     * @param bool $home true, wenn das Heimteam geholt werden soll
     *
     * @return StatsDataBag[]
     */
    public function getCoachBags($match, $home)
    {
        $type = $home ? 'home' : 'guest';
        $uid = $match->getProperty('coach_'.$type);
        $bags = [];
        if ($uid <= 0) {
            return $bags; // skip dummy records
        }
        $bag = $this->createProfileBag($uid, $match, $home, 'coach');
        $bags[] = $bag;

        return $bags;
    }

    /**
     * Liefert die DataBags für den Schiedsrichter eines Spiels.
     *
     * @param Fixture $match
     * @param bool $home true, wenn das Heimteam geholt werden soll
     *
     * @return StatsDataBag[]]
     */
    public function getRefereeBags(Fixture $match, $home)
    {
        $refereeUid = $match->getProperty('referee');
        $ids = $match->getProperty('referee');
        if (strlen($match->getProperty('assists')) > 0) {
            // Auch Assistenten anhängen
            if (strlen($ids) > 0) {
                $ids .= ','.$match->getProperty('assists');
            } else {
                $ids = $match->getProperty('assists');
            }
        }

        $bags = [];
        $refIds = Strings::intExplode(',', $ids);
        foreach ($refIds as $uid) {
            if ($uid <= 0) {
                continue; // skip dummy records
            }
            $bag = $this->createProfileBag($uid, $match, $home, 'referee');
            $bag->setType('assist', $refereeUid == $uid ? 0 : 1);
            $bag->setType('mainref', $refereeUid == $uid ? 1 : 0);
            $bags[] = $bag;
        }

        return $bags;
    }

    /**
     * @param int $uid
     * @param Fixture $match
     * @param bool $home
     * @param string $profileField
     *
     * @return StatsDataBag
     *
     * @throws \Exception
     */
    private function createProfileBag($uid, $match, $home, $profileField)
    {
        /** @var StatsDataBag $bag */
        $bag = tx_rnbase::makeInstance(StatsDataBag::class);
        $bag->setParentUid($uid);
        // Hier noch die allgemeinen Daten rein!
        $bag->setType('t3match', $match->getUid());
        $bag->setType($profileField, $uid);
        $competition = $match->getCompetition();
        $bag->setType('competition', $competition->getUid());
        $bag->setType('saison', $competition->getSaisonUid());
        // Altersgruppe ist zunächst die AG des Teams, danach die des Wettbewerbs
        $team = $home ? $match->getHome() : $match->getGuest();
        $groupUid = $this->getGroupUid($team, $competition);
        $bag->setType('agegroup', $groupUid);
        $bag->setType('team', $team->getUid());
        $bag->setType('club', $team->getClubUid());
        $bag->setType('ishome', $home ? 1 : 0);

        $team = $home ? $match->getGuest() : $match->getHome();
        $groupUid = $this->getGroupUid($team, $competition);
        $bag->setType('agegroupopp', $groupUid);
        $bag->setType('clubopp', $team->getClubUid());

        return $bag;
    }

    private function getGroupUid(Team $team, Competition $competition)
    {
        $groupUid = $team->getGroupUid();
        if (!$groupUid) {
            $groupUid = $competition->getFirstGroupUid();
        }

        return $groupUid;
    }

    /**
     * Search database for player stats.
     *
     * @param array $fields
     * @param array $options
     *
     * @return array of tx_a4base_models_trade
     */
    public function searchPlayerStats($fields, $options)
    {
        $searcher = SearchBase::getInstance(SearchPlayerStats::class);

        return $searcher->search($fields, $options);
    }

    /**
     * Search database for coach stats.
     *
     * @param array $fields
     * @param array $options
     *
     * @return array of tx_a4base_models_trade
     */
    public function searchCoachStats($fields, $options)
    {
        $searcher = SearchBase::getInstance(SearchCoachStats::class);

        return $searcher->search($fields, $options);
    }

    /**
     * Search database for referee stats.
     *
     * @param array $fields
     * @param array $options
     *
     * @return array of tx_a4base_models_trade
     */
    public function searchRefereeStats($fields, $options)
    {
        $searcher = SearchBase::getInstance(SearchRefereeStats::class);

        return $searcher->search($fields, $options);
    }

    /**
     * Returns all registered services for player statistics.
     *
     * @return array
     */
    public function lookupPlayerServices()
    {
        return $this->lookupStatsServices(PlayerStatsInterface::INDEXER_TYPE);
    }

    /**
     * Returns all registered services for coach statistics.
     *
     * @return array
     */
    public function lookupCoachServices()
    {
        return $this->lookupStatsServices(CoachStatsInterface::INDEXER_TYPE);
    }

    /**
     * Returns all registered services for referee statistics.
     *
     * @return array
     */
    public function lookupRefereeServices()
    {
        return $this->lookupStatsServices(RefereeStatsInterface::INDEXER_TYPE);
    }

    /**
     * Returns all registered services for statistics.
     *
     * @return array
     */
    private function lookupStatsServices($key)
    {
        $indexer = $this->indexerProvider->getStatsIndexerByType($key);

        return $indexer;
    }
}
