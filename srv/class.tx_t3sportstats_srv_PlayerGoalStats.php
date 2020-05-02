<?php
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
tx_rnbase::load('tx_cfcleague_util_MatchNote');
tx_rnbase::load('tx_t3sportstats_util_Config');

/**
 * @author Rene Nitzsche
 */
class tx_t3sportstats_srv_PlayerGoalStats extends Tx_Rnbase_Service_Base
{
    private $types = array();

    /**
     * Update statistics for a player
     * goalshome, goalsaway, goalsjoker.
     *
     * @param tx_t3sportstats_util_DataBag $dataBag
     * @param tx_cfcleague_models_Match $match
     * @param tx_t3sportstats_util_MatchNoteProvider $mnProv
     */
    public function indexPlayerStats($dataBag, $match, $mnProv, $isHome)
    {
        // Wir betrachten das Spiel für einen bestimmten Spieler
        $profId = $dataBag->getParentUid();
        $notes = $mnProv->getMatchNotes4Profile($profId);
        $startPlayer = $this->isStartPlayer($profId, $match, $isHome);
        $statTypes = tx_t3sportstats_util_Config::getPlayerStatsSimple();
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
     * @param tx_cfcleague_models_Match $match
     * @param bool $isHome
     */
    private function isStartPlayer($player, $match, $isHome)
    {
        $startPlayer = array_flip(Tx_Rnbase_Utility_Strings::intExplode(',', $isHome ? $match->getProperty('players_home') : $match->getProperty('players_guest')));

        return array_key_exists($player, $startPlayer);
    }

    private function isType($type, $typeList)
    {
        if (!array_key_exists($typeList, $this->types)) {
            $this->types[$typeList] = array_flip(Tx_Rnbase_Utility_Strings::intExplode(',', $typeList));
        }
        $types = $this->types[$typeList];

        return array_key_exists($type, $types);
    }
}
