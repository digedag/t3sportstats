<?php

namespace System25\T3sports\Utility;

use System25\T3sports\Model\MatchNote;

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
 * Data container.
 */
class StatsMatchNoteProvider
{
    private $notes = [];

    private $notesHome = [];

    private $notesGuest = [];

    private function __construct($notes)
    {
        $this->setMatchNotes($notes);
    }

    /**
     * Create a new instance.
     *
     * @param array $notes
     *
     * @return StatsMatchNoteProvider
     */
    public static function createInstance($notes)
    {
        return new self($notes);
    }

    private function setMatchNotes($notes)
    {
        $this->notes = is_array($notes) ? $notes : [];
        $this->notesHome = [];
        $this->notesHome['_all'] = [];
        $this->notesGuest = [];
        $this->notesGuest['_all'] = [];
        foreach ($notes as $note) {
            $profile = $note->getPlayer();
            if ($note->isHome()) {
                // Die Trennung der Spieler in verschiedene Array kann sinnvoll sein,
                // weil ein Spieler auch mal in beiden Teams spielen könnte. Abschiedsspiele
                $this->notesHome[$profile][] = $note;
                $this->notesHome['_all'][] = $note;
            }
            if ($note->isGuest()) {
                $this->notesGuest[$profile][] = $note;
                $this->notesGuest['_all'][] = $note;
            }
        }
    }

    /**
     * @return MatchNote[]
     */
    public function getMatchNotes()
    {
        return $this->notes;
    }

    /**
     * @return MatchNote[]
     */
    public function getMatchNotesHome()
    {
        return $this->notesHome['_all'];
    }

    /**
     * @return MatchNote[]
     */
    public function getMatchNotesGuest()
    {
        return $this->notesGuest['_all'];
    }

    /**
     * Returns all match notes for a profile.
     *
     * @param int $profileUid
     *
     * @return MatchNote[]
     */
    public function getMatchNotes4Profile($profileUid)
    {
        if (array_key_exists($profileUid, $this->notesHome)) {
            return $this->notesHome[$profileUid];
        }
        if (array_key_exists($profileUid, $this->notesGuest)) {
            return $this->notesGuest[$profileUid];
        }

        return [];
    }
}
