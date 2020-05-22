<?php

namespace System25\T3sports\StatsIndexer;

use System25\T3sports\Utility\StatsDataBag;
use System25\T3sports\Utility\StatsMatchNoteProvider;
use Sys25\RnBase\Typo3Wrapper\Service\AbstractService;
use System25\T3sports\Sports\ServiceLocator;
use System25\T3sports\Sports\MatchInfo;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2020 Rene Nitzsche (rene@system25.de)
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
 * @author Rene Nitzsche
 */
class PlayerTimeStats extends AbstractService
{
    private $types = [];

    private $serviceLocator;

    public function __construct(ServiceLocator $locator = null)
    {
        $this->serviceLocator = $locator ? $locator : new ServiceLocator();
    }

    /**
     * Update statistics for a player
     * playtime, played.
     *
     * @param StatsDataBag $dataBag
     * @param \tx_cfcleague_models_Match $match
     * @param StatsMatchNoteProvider $mnProv
     */
    public function indexPlayerStats($dataBag, $match, $mnProv, $isHome)
    {
        // Wir betrachten das Spiel für einen bestimmten Spieler
        $profId = $dataBag->getParentUid();
        $notes = $mnProv->getMatchNotes4Profile($profId);
        $startMin = $this->isStartPlayer($profId, $match, $isHome) ? 0 : -1;
        $isEndPlayer = 0 == $startMin ? true : false;
        if ($isEndPlayer) {
            $dataBag->setType('played', 1);
        }
        $time = 0;

        foreach ($notes as $note) {
            if (\tx_cfcleague_util_MatchNote::isChangeIn($note)) {
                $startMin = $note->getMinute();
                $isEndPlayer = true;
                $dataBag->setType('played', 1);
            } elseif (
                    \tx_cfcleague_util_MatchNote::isChangeOut($note) ||
                    \tx_cfcleague_util_MatchNote::isCardYellowRed($note) ||
                    \tx_cfcleague_util_MatchNote::isCardRed($note)) {
                $time = $note->getMinute() - $startMin + $time;
                $isEndPlayer = false;
            }
        }
        if ($isEndPlayer) {
            $endTime = $this->retrieveEndTime($match);
            $time = $endTime - $startMin + $time;
            $time = $time ? $time : 1; // Give the player at least 1 minute.
        }
        $dataBag->addType('playtime', $time);
    }

    protected function retrieveEndTime(\tx_cfcleague_models_Match $match)
    {
        $sports = $this->serviceLocator->getSportsService($match->getCompetition()->getSports());
        $matchInfo = $sports->getMatchInfo();
        $key = $match->isExtraTime() ? MatchInfo::MATCH_EXTRA_TIME : MatchInfo::MATCH_TIME;
        $ret = $matchInfo->getInfo($key);

        return null == $ret ? 90 : $ret;
    }

    /**
     * @param \tx_cfcleague_models_Match $match
     * @param bool $isHome
     */
    private function isStartPlayer($player, $match, $isHome)
    {
        $startPlayer = array_flip(
            \Tx_Rnbase_Utility_Strings::intExplode(',', $isHome ? $match->getProperty('players_home') : $match->getProperty('players_guest'))
        );

        return array_key_exists($player, $startPlayer);
    }

    private function isType($type, $typeList)
    {
        if (!array_key_exists($typeList, $this->types)) {
            $this->types[$typeList] = array_flip(\Tx_Rnbase_Utility_Strings::intExplode(',', $typeList));
        }
        $types = $this->types[$typeList];

        return array_key_exists($type, $types);
    }
}
