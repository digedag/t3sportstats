<?php

namespace System25\T3sports\StatsIndexer;

use System25\T3sports\Utility\StatsConfig;
use System25\T3sports\Utility\StatsDataBag;
use System25\T3sports\Utility\StatsMatchNoteProvider;
use Sys25\RnBase\Typo3Wrapper\Service\AbstractService;

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
class CoachStats extends AbstractService
{
    private $types = [];

    /**
     * Update statistics for a coach.
     *
     * @param StatsDataBag $dataBag
     * @param \tx_cfcleague_models_Match $match
     * @param StatsMatchNoteProvider $mnProv
     * @param bool $isHome
     */
    public function indexCoachStats($dataBag, $match, $mnProv, $isHome)
    {
        // Wir betrachten das Spiel für einen bestimmten Spieler
        $this->indexSimple($dataBag, $mnProv, $isHome);
        $this->indexWinLoose($dataBag, $match, $isHome);
        $this->indexGoals($dataBag, $match, $isHome);
        $this->indexJokerGoals($dataBag, $match, $isHome, $mnProv);
    }

    /**
     * @param StatsDataBag $dataBag
     * @param \tx_cfcleague_models_Match $match
     * @param bool $isHome
     * @param StatsMatchNoteProvider $mnProv
     */
    private function indexJokerGoals($dataBag, $match, $isHome, $mnProv)
    {
        // Wir benötigen die Events der gesamten Mannschaft
        $notes = $isHome ? $mnProv->getMatchNotesHome() : $mnProv->getMatchNotesGuest();
        $statTypes = StatsConfig::getPlayerStatsSimple();
        $goalTypes = $statTypes['goals']['types'];
        foreach ($notes as $note) {
            if ($this->isType($note->getType(), $goalTypes)) {
                $playerUid = $note->getPlayer();
                $startPlayer = $this->isStartPlayer($playerUid, $match, $isHome);
                if (!$startPlayer) {
                    $dataBag->addType('goalsjoker', 1);
                }
            }
        }
    }

    /**
     * @param StatsDataBag $dataBag
     * @param \tx_cfcleague_models_Match $match
     * @param bool $isHome
     */
    private function indexGoals($dataBag, $match, $isHome)
    {
        $goals = $isHome ? $match->getGoalsHome() : $match->getGoalsGuest();
        $dataBag->addType('goals', $goals);
        $dataBag->addType($isHome ? 'goalshome' : 'goalsaway', $goals);

        $goals = !$isHome ? $match->getGoalsHome() : $match->getGoalsGuest();
        $dataBag->addType('goalsagainst', $goals);
        $dataBag->addType($isHome ? 'goalshomeagainst' : 'goalsawayagainst', $goals);
    }

    /**
     * @param StatsDataBag $dataBag
     * @param \tx_cfcleague_models_Match $match
     * @param bool $isHome
     */
    private function indexWinLoose($dataBag, $match, $isHome)
    {
        $dataBag->setType('played', 1);
        $toto = $match->getToto();
        $type = 'draw';
        if (1 == $toto && $isHome || 2 == $toto && !$isHome) {
            $type = 'win';
        } elseif (2 == $toto && $isHome || 1 == $toto && !$isHome) {
            $type = 'loose';
        }
        $dataBag->addType($type, 1);
    }

    /**
     * @param StatsDataBag $dataBag
     * @param StatsMatchNoteProvider $mnProv
     */
    private function indexSimple($dataBag, $mnProv, $isHome)
    {
        // Wir benötigen die Events der gesamten Mannschaft
        $notes = $isHome ? $mnProv->getMatchNotesHome() : $mnProv->getMatchNotesGuest();

        if (!$notes || 0 == count($notes)) {
            return;
        }
        $statTypes = StatsConfig::getCoachStatsSimple();
        foreach ($notes as $note) {
            foreach ($statTypes as $type => $info) {
                // Entspricht die Note dem Type in der Info
                if ($this->isType($note->getType(), $info['types'])) {
                    $dataBag->addType($type, 1);
                }
            }
        }
    }

    private function isType($type, $typeList)
    {
        if (!array_key_exists($typeList, $this->types)) {
            $this->types[$typeList] = array_flip(\Tx_Rnbase_Utility_Strings::intExplode(',', $typeList));
        }
        $types = $this->types[$typeList];

        return array_key_exists($type, $types);
    }

    /**
     * @param int $player
     *            profile uid
     * @param \tx_cfcleague_models_Match $match
     * @param bool $isHome
     */
    private function isStartPlayer($player, $match, $isHome)
    {
        $startPlayer = array_flip(\Tx_Rnbase_Utility_Strings::intExplode(',', $isHome ? $match->getProperty('players_home') : $match->getProperty('players_guest')));

        return array_key_exists($player, $startPlayer);
    }
}
