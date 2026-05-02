<?php

namespace System25\T3sports\Hooks;

use System25\T3sports\Model\Fixture;
use System25\T3sports\Model\MatchNote;
use System25\T3sports\Model\Repository\CompetitionRepository;
use System25\T3sports\Model\Repository\MatchRepository;
use tx_rnbase;

/***************************************************************
*  Copyright notice
*
*  (c) 2010-2026 Rene Nitzsche <rene@system25.de>
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

class StatsTCEHook
{
    private $competitionRepo;
    private $fixtureRepo;

    public function __construct(
        CompetitionRepository $competitionRepo,
        MatchRepository $fixtureRepo,
    ) {
        $this->competitionRepo = $competitionRepo;
        $this->fixtureRepo = $fixtureRepo;
    }

    /**
     * Wir müssen dafür sorgen, daß die neuen IDs der Teams im Wettbewerb und Spielen
     * verwendet werden.
     */
    //	public function processDatamap_preProcessFieldArray(&$incomingFieldArray, $table, $id, &$tcemain)  {
    //	}

    /**
     * Nachbearbeitungen, unmittelbar BEVOR die Daten gespeichert werden. Das POST bezieht sich
     * auf die Arbeit der TCE und nicht auf die Speicherung in der DB.
     *
     * @param string $status new oder update
     * @param string $table Name der Tabelle
     * @param int $id UID des Datensatzes
     * @param array $fieldArray Felder des Datensatzes, die sich ändern
     * @param tce_main $tcemain
     */
    //	public function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, &$tce) {
    //	}

    /**
     * Nachbearbeitungen, unmittelbar NACHDEM die Daten gespeichert wurden.
     *
     * @param string $status new oder update
     * @param string $table Name der Tabelle
     * @param int $id UID des Datensatzes
     * @param array $fieldArray Felder des Datensatzes, die sich ändern
     * @param tce_main $tcemain
     */
    public function processDatamap_afterDatabaseOperations($status, $table, $id, $fieldArray, &$tcemain)
    {
        $compUid = null;
        if ('tx_cfcleague_match_notes' == $table) {
            // Bei einer Änderung von MatchNotes dürfen wir nur reagieren, wenn das Spiel schon beendet ist.
            $id = 'new' === $status ? $tcemain->substNEWwithIDs[$id] : $id;
            /** @var MatchNote $note */
            $note = tx_rnbase::makeInstance(MatchNote::class, $id);
            if (!($note->getPlayerHome() || $note->getPlayerGuest())) {
                // Ohne Spieler ist das für die Statistik nicht relevant.
                return;
            }
            if ($note->getProperty('game')) {
                /** @var Fixture $fixture */
                $fixture = $this->fixtureRepo->findByUid($note->getProperty('game'));
                if (!$fixture->isFinished()) {
                    return;
                }
                $compUid = $fixture->getProperty('competition');
            }
        }
        if ('tx_cfcleague_games' == $table && 'new' != $status) {
            if (isset($fieldArray['status'])) {
                /** @var Fixture $fixture */
                $fixture = $this->fixtureRepo->findByUid($id);
                if (!$fixture->isFinished()) {
                    return;
                }
                $compUid = $fixture->getProperty('competition');
            }
        }
        if ($compUid) {
            $competition = $this->competitionRepo->findByUid($compUid);
            if ($competition && $competition->getProperty('statsenabled')) {
                $competition->setStatsrefresh(1);
                $competition->setStatsrefreshed('');
                $this->competitionRepo->persist($competition);
            }
        }
    }
}
