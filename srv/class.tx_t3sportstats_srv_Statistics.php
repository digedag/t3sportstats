<?php
use System25\T3sports\Search\SearchCoachStats;
use System25\T3sports\Search\SearchPlayerStats;
use System25\T3sports\Search\SearchRefereeStats;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2017 Rene Nitzsche (rene@system25.de)
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
tx_rnbase::load('Tx_Rnbase_Service_Base');
tx_rnbase::load('Tx_Rnbase_Utility_Strings');
tx_rnbase::load('tx_rnbase_util_Logger');
tx_rnbase::load('tx_rnbase_util_Dates');
tx_rnbase::load('tx_t3sportstats_util_MatchNoteProvider');
tx_rnbase::load('Tx_Rnbase_Database_Connection');

/**
 * Service for accessing team information.
 *
 * @author Rene Nitzsche
 */
class tx_t3sportstats_srv_Statistics extends Tx_Rnbase_Service_Base
{
    private $statsSrvArr = array();

    /**
     * Update statistics for a competition.
     *
     * @param tx_cfcleague_models_Competition $competition
     */
    public function indexPlayerStatsByCompetition($competition)
    {
        // Der Service lädt alle DatenServices für Spielerdaten
        // Danach lädt er die Spiele eines Wettbewerbs
        // Für jedes Spiel werden die Events geladen
        // Anschließend bekommt jeder Service das Spiel, den Spieler und die Events übergeben
        // In ein Datenarray legt er die relevanten Daten für den Spieler
        $mSrv = tx_cfcleague_util_ServiceRegistry::getMatchService();
        $builder = $mSrv->getMatchTableBuilder();
        $builder->setCompetitions($competition->getUid());
        $builder->setStatus(2);
        $fields = $options = [];
        $builder->getFields($fields, $options);
        $matches = $mSrv->search($fields, $options);
        $this->indexStatsByMatches($matches);
    }

    public function indexStatsByMatches($matches)
    {
        tx_rnbase_util_Logger::info('Start player statistics run for '.count($matches).' matches.', 't3sportstats');
        $time = microtime(true);
        $memStart = memory_get_usage();

        // Über alle Spiele iterieren und die Spieler an die Services geben
        for ($j = 0, $mc = count($matches); $j < $mc; ++$j ) {
            $matchNotes = tx_cfcleague_util_ServiceRegistry::getMatchService()->retrieveMatchNotes($matches[$j], true);
            $mnProv = tx_t3sportstats_util_MatchNoteProvider::createInstance($matchNotes);
            // handle Hometeam
            $this->indexPlayerData($matches[$j], $mnProv, true);
            $this->indexCoachData($matches[$j], $mnProv, true);
            $this->indexRefereeData($matches[$j], $mnProv, true);
            // handle Guestteam
            $this->indexPlayerData($matches[$j], $mnProv, false);
            $this->indexCoachData($matches[$j], $mnProv, false);
            $this->indexRefereeData($matches[$j], $mnProv, false);
        }
        if (tx_rnbase_util_Logger::isInfoEnabled()) {
            $memEnd = memory_get_usage();
            tx_rnbase_util_Logger::info('Player statistics finished.', 't3sportstats', array(
                'Execution Time' => (microtime(true) - $time),
                'Matches' => count($matches),
                'Memory Start' => $memStart,
                'Memory End' => $memEnd,
                'Memory Consumed' => ($memEnd - $memStart),
            ));
        }
    }

    /**
     * Indizierung der Daten und Speicherung in der DB.
     *
     * @param tx_cfcleague_models_Match $match
     * @param tx_t3sportstats_util_MatchNoteProvider $mnProv
     * @param bool $homeTeam
     */
    private function indexPlayerData($match, $mnProv, $homeTeam)
    {
        // Services laden
        $servicesArr = $this->lookupPlayerServices();

        $del = $this->clearPlayerData($match, $homeTeam);
        tx_rnbase_util_Logger::debug('Player statistics: '.$del.' old records deleted.', 't3sportstats');

        $dataBags = $this->getPlayerBags($match, $homeTeam);
        for ($i = 0, $servicesArrCnt = count($servicesArr); $i < $servicesArrCnt; ++$i ) {
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
     * @param tx_cfcleague_models_Match $match
     * @param tx_t3sportstats_util_MatchNoteProvider $mnProv
     * @param bool $homeTeam
     */
    private function indexCoachData($match, $mnProv, $homeTeam)
    {
        // Services laden
        $servicesArr = $this->lookupCoachServices();

        $del = $this->clearCoachData($match, $homeTeam);
        tx_rnbase_util_Logger::debug('Coach statistics: '.$del.' old records deleted.', 't3sportstats');

        $dataBags = $this->getCoachBags($match, $homeTeam);
        for ($i = 0, $servicesArrCnt = count($servicesArr); $i < $servicesArrCnt; ++$i ) {
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
     * @param tx_cfcleague_models_Match $match
     * @param tx_t3sportstats_util_MatchNoteProvider $mnProv
     * @param bool $homeTeam
     */
    private function indexRefereeData($match, $mnProv, $homeTeam)
    {
        // Services laden
        $servicesArr = $this->lookupRefereeServices();
        $del = $this->clearRefereeData($match, $homeTeam);
        tx_rnbase_util_Logger::debug('Referee statistics: '.$del.' old records deleted.', 't3sportstats');

        $dataBags = $this->getRefereeBags($match, $homeTeam);
        for ($i = 0, $servicesArrCnt = count($servicesArr); $i < $servicesArrCnt; ++$i ) {
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
     * @param tx_cfcleague_models_Match $match
     */
    private function clearPlayerData($match, $isHome)
    {
        $where = 't3match = '.$match->getUid().' AND ishome='.($isHome ? 1 : 0);

        return Tx_Rnbase_Database_Connection::getInstance()->doDelete('tx_t3sportstats_players', $where);
    }

    /**
     * Delete all coach data in database for a match.
     *
     * @param tx_cfcleague_models_Match $match
     */
    private function clearCoachData($match, $isHome)
    {
        $where = 't3match = '.$match->getUid().' AND ishome='.($isHome ? 1 : 0);

        return Tx_Rnbase_Database_Connection::getInstance()->doDelete('tx_t3sportstats_coachs', $where);
    }

    /**
     * Delete all referee data in database for a match.
     *
     * @param tx_cfcleague_models_Match $match
     */
    private function clearRefereeData($match, $isHome)
    {
        $where = 't3match = '.$match->getUid().' AND ishome='.($isHome ? 1 : 0);

        return Tx_Rnbase_Database_Connection::getInstance()->doDelete('tx_t3sportstats_referees', $where);
    }

    private function savePlayerData($dataBags)
    {
        $now = tx_rnbase_util_Dates::datetime_tstamp2mysql(time());
        foreach ($dataBags as $dataBag) {
            $data = $dataBag->getTypeValues();
            $data['crdate'] = $now;
            Tx_Rnbase_Database_Connection::getInstance()->doInsert('tx_t3sportstats_players', $data);
        }
    }

    private function saveCoachData($dataBags)
    {
        $now = tx_rnbase_util_Dates::datetime_tstamp2mysql(time());
        foreach ($dataBags as $dataBag) {
            $data = $dataBag->getTypeValues();
            $data['crdate'] = $now;
            Tx_Rnbase_Database_Connection::getInstance()->doInsert('tx_t3sportstats_coachs', $data);
        }
    }

    private function saveRefereeData($dataBags)
    {
        $now = tx_rnbase_util_Dates::datetime_tstamp2mysql(time());
        foreach ($dataBags as $dataBag) {
            $data = $dataBag->getTypeValues();
            $data['crdate'] = $now;
            Tx_Rnbase_Database_Connection::getInstance()->doInsert('tx_t3sportstats_referees', $data);
        }
    }

    /**
     * Liefert die DataBags für die Spieler eines beteiligten Teams.
     *
     * @param tx_cfcleague_models_Match $match
     * @param bool $home
     *            true, wenn das Heimteam geholt werden soll
     *
     * @return array[tx_t3sportstats_util_DataBag]
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
        $bags = array();
        $playerIds = Tx_Rnbase_Utility_Strings::intExplode(',', $ids);
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
     * @param tx_cfcleague_models_Match $match
     * @param bool $home
     *            true, wenn das Heimteam geholt werden soll
     *
     * @return array[tx_t3sportstats_util_DataBag]
     */
    public function getCoachBags($match, $home)
    {
        $type = $home ? 'home' : 'guest';
        $uid = $match->getProperty('coach_'.$type);
        $bags = array();
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
     * @param tx_cfcleague_models_Match $match
     * @param bool $home
     *            true, wenn das Heimteam geholt werden soll
     *
     * @return array[tx_t3sportstats_util_DataBag]
     */
    public function getRefereeBags($match, $home)
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

        $bags = array();
        $refIds = Tx_Rnbase_Utility_Strings::intExplode(',', $ids);
        foreach ($refIds as $uid) {
            if ($uid <= 0) {
                continue; // skip dummy records
            }
            $bag = $this->createProfileBag($uid, $match, $home, 'referee');
            $bag->setType('assist', ($refereeUid == $uid ? 0 : 1));
            $bag->setType('mainref', ($refereeUid == $uid ? 1 : 0));
            $bags[] = $bag;
        }

        return $bags;
    }

    private function createProfileBag($uid, $match, $home, $profileField)
    {
        $bag = tx_rnbase::makeInstance('tx_t3sportstats_util_DataBag');
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

    private function getGroupUid($team, $competition)
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
        tx_rnbase::load('tx_rnbase_util_SearchBase');
        $searcher = tx_rnbase_util_SearchBase::getInstance(SearchPlayerStats::class);

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
        tx_rnbase::load('tx_rnbase_util_SearchBase');
        $searcher = tx_rnbase_util_SearchBase::getInstance(SearchCoachStats::class);

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
        tx_rnbase::load('tx_rnbase_util_SearchBase');
        $searcher = tx_rnbase_util_SearchBase::getInstance(SearchRefereeStats::class);

        return $searcher->search($fields, $options);
    }

    /**
     * Returns all registered services for player statistics.
     *
     * @return array
     */
    public function lookupPlayerServices()
    {
        return $this->lookupStatsServices('t3sportsPlayerStats');
    }

    /**
     * Returns all registered services for coach statistics.
     *
     * @return array
     */
    public function lookupCoachServices()
    {
        return $this->lookupStatsServices('t3sportsCoachStats');
    }

    /**
     * Returns all registered services for referee statistics.
     *
     * @return array
     */
    public function lookupRefereeServices()
    {
        return $this->lookupStatsServices('t3sportsRefereeStats');
    }

    /**
     * Returns all registered services for statistics.
     *
     * @return array
     */
    private function lookupStatsServices($key)
    {
        if (!array_key_exists($key, $this->statsSrvArr)) {
            $srvArr = tx_rnbase_util_Misc::lookupServices($key);
            $this->statsSrvArr[$key] = array();
            foreach ($srvArr as $subType => $srvData) {
                $this->statsSrvArr[$key][] = tx_rnbase_util_Misc::getService($key, $subType);
            }
        }

        return $this->statsSrvArr[$key];
    }
}
