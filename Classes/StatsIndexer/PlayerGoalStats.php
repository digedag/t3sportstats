<?php

namespace System25\T3sports\StatsIndexer;

use Sys25\RnBase\Utility\Strings;
use System25\T3sports\Model\Fixture;
use System25\T3sports\Utility\StatsConfig;
use System25\T3sports\Utility\StatsDataBag;
use System25\T3sports\Utility\StatsMatchNoteProvider;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2023 Rene Nitzsche (rene@system25.de)
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
class PlayerGoalStats implements PlayerStatsInterface
{
    private $types = [];

    public function getIndexerType()
    {
        return self::INDEXER_TYPE;
    }

    /**
     * Update statistics for a player
     * goalshome, goalsaway, goalsjoker.
     *
     * @param StatsDataBag $dataBag
     * @param Fixture $match
     * @param StatsMatchNoteProvider $mnProv
     */
    public function indexPlayerStats(StatsDataBag $dataBag, Fixture $match, StatsMatchNoteProvider $mnProv, bool $isHome)
    {
        // Wir betrachten das Spiel für einen bestimmten Spieler
        $profId = $dataBag->getParentUid();
        $notes = $mnProv->getMatchNotes4Profile($profId);
        $startPlayer = $this->isStartPlayer($profId, $match, $isHome);
        $statTypes = StatsConfig::getPlayerStatsSimple();
        $goalTypes = $statTypes['goals']['types'];
        $homeAwayType = $isHome ? 'goalshome' : 'goalsaway';
        foreach ($notes as $note) {
            if ($this->isType($note->getType(), $goalTypes)) {
                $dataBag->addType($homeAwayType, 1);
                if (!$startPlayer) {
                    $dataBag->addType('goalsjoker', 1);
                }
            }
        }
        // $dataBag->addType('playtime', $time);
    }

    /**
     * @param Fixture $match
     * @param bool $isHome
     */
    private function isStartPlayer($player, $match, $isHome)
    {
        $startPlayer = array_flip(Strings::intExplode(',', $isHome ? $match->getProperty('players_home') : $match->getProperty('players_guest')));

        return array_key_exists($player, $startPlayer);
    }

    private function isType($type, $typeList)
    {
        if (!array_key_exists($typeList, $this->types)) {
            $this->types[$typeList] = array_flip(Strings::intExplode(',', $typeList));
        }
        $types = $this->types[$typeList];

        return array_key_exists($type, $types);
    }
}
