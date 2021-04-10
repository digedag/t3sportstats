<?php

namespace System25\T3sports\StatsIndexer;

use Sys25\RnBase\Typo3Wrapper\Service\AbstractService;
use System25\T3sports\Utility\StatsConfig;
use System25\T3sports\Utility\StatsDataBag;
use System25\T3sports\Utility\StatsMatchNoteProvider;

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
class PlayerStats extends AbstractService
{
    private $types = [];

    /**
     * Update statistics for a player.
     *
     * @param StatsDataBag $dataBag
     * @param \tx_cfcleague_models_Match $match
     * @param StatsMatchNoteProvider $mnProv
     * @param bool $isHome
     */
    public function indexPlayerStats($dataBag, $match, $mnProv, $isHome)
    {
        // Wir betrachten das Spiel für einen bestimmten Spieler
        $this->indexSimple($dataBag, $mnProv);
        $this->indexWinLoose($dataBag, $match, $isHome);
    }

    /**
     * @param StatsDataBag $dataBag
     * @param \tx_cfcleague_models_Match $match
     * @param bool $isHome
     */
    private function indexWinLoose($dataBag, $match, $isHome)
    {
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
    private function indexSimple($dataBag, $mnProv)
    {
        $profId = $dataBag->getParentUid();
        // Wir benötigen die Events des Spielers
        $notes = $mnProv->getMatchNotes4Profile($profId);

        if (!$notes || 0 == count($notes)) {
            return;
        }
        $statTypes = StatsConfig::getPlayerStatsSimple();
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
}
