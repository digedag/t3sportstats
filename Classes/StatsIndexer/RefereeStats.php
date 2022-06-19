<?php

namespace System25\T3sports\StatsIndexer;

use Sys25\RnBase\Typo3Wrapper\Service\AbstractService;
use Sys25\RnBase\Utility\Strings;
use System25\T3sports\Model\Match;
use System25\T3sports\Utility\StatsConfig;
use System25\T3sports\Utility\StatsDataBag;
use System25\T3sports\Utility\StatsMatchNoteProvider;

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
 * Die Schiedsrichterstatistiken werden immer aus Vereinssicht erstellt.
 * Dadurch müssen für jedes
 * Spiel auch zwei Datensätze angelegt werden, einer für jeden Verein.
 *
 * @author Rene Nitzsche
 */
class RefereeStats extends AbstractService
{
    private $types = [];

    /**
     * Update statistics for a referee.
     *
     * @param StatsDataBag $dataBag
     * @param Match $match
     * @param StatsMatchNoteProvider $mnProv
     * @param bool $isHome
     */
    public function indexRefereeStats($dataBag, $match, $mnProv, $isHome)
    {
        // Wir betrachten das Spiel für einen bestimmten SR
        if (!$this->isAssist($dataBag)) {
            // Diese Daten sind für die SRA nicht relevant
            $this->indexSimple($dataBag, $mnProv, $isHome);
            $this->indexPenalties($dataBag, $match, $isHome, $mnProv);
        }
        $this->indexWinLoose($dataBag, $match, $isHome);
        // $this->indexGoals($dataBag, $match, $isHome);
    }

    /**
     * @param StatsDataBag $dataBag
     * @param Match $match
     * @param bool $isHome
     * @param StatsMatchNoteProvider $mnProv
     */
    private function indexPenalties($dataBag, $match, $isHome, $mnProv)
    {
        $this->indexOwnAgainst('penalty', $dataBag, $match, $isHome, $mnProv);
        $this->indexOwnAgainst('goalspenalty', $dataBag, $match, $isHome, $mnProv);
        $this->indexOwnAgainst('cardyellow', $dataBag, $match, $isHome, $mnProv);
        $this->indexOwnAgainst('cardyr', $dataBag, $match, $isHome, $mnProv);
        $this->indexOwnAgainst('cardred', $dataBag, $match, $isHome, $mnProv);
    }

    /**
     * @param StatsDataBag $dataBag
     * @param Match $match
     * @param bool $isHome
     * @param StatsMatchNoteProvider $mnProv
     */
    private function indexOwnAgainst($baseType, $dataBag, $match, $isHome, $mnProv)
    {
        // Wir benötigen die Events der gesamten Mannschaft
        $notes = $mnProv->getMatchNotes();
        $statTypes = StatsConfig::getRefereeStatsSimple();
        $noteTypes = $statTypes[$baseType]['types'];
        foreach ($notes as $note) {
            if ($this->isType($note->getType(), $noteTypes)) {
                if ($note->isHome()) {
                    $key = $isHome ? 'own' : 'against';
                } else {
                    $key = $isHome ? 'against' : 'own';
                }
                $dataBag->addType($baseType.$key, 1);
            }
        }
    }

    /**
     * @param StatsDataBag $dataBag
     * @param Match $match
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
     */
    private function isAssist($dataBag)
    {
        return intval($dataBag->getTypeValue('assist')) > 0;
    }

    /**
     * @param StatsDataBag $dataBag
     * @param StatsMatchNoteProvider $mnProv
     */
    private function indexSimple($dataBag, $mnProv, $isHome)
    {
        // Wir benötigen die Events des gesamten Spiels
        // $notes = $isHome ? $mnProv->getMatchNotesHome() : $mnProv->getMatchNotesGuest();
        $notes = $mnProv->getMatchNotes();

        if (!$notes || 0 == count($notes)) {
            return;
        }
        $statTypes = StatsConfig::getRefereeStatsSimple();
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
            $this->types[$typeList] = array_flip(Strings::intExplode(',', $typeList));
        }
        $types = $this->types[$typeList];

        return array_key_exists($type, $types);
    }
}
