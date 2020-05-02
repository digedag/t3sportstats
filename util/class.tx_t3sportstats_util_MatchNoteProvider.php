<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010-2016 Rene Nitzsche (rene@system25.de)
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
class tx_t3sportstats_util_MatchNoteProvider
{
    private $notes = array();

    private $notesHome = array();

    private $notesGuest = array();

    private function __construct($notes)
    {
        $this->setMatchNotes($notes);
    }

    /**
     * Create a new instance.
     *
     * @param array $notes
     *
     * @return tx_t3sportstats_util_MatchNoteProvider
     */
    public static function createInstance($notes)
    {
        return new self($notes);
    }

    private function setMatchNotes($notes)
    {
        $this->notes = is_array($notes) ? $notes : array();
        $this->notesHome = array();
        $this->notesHome['_all'] = array();
        $this->notesGuest = array();
        $this->notesGuest['_all'] = array();
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
     * @return array[tx_cfcleague_models_MatchNote]
     */
    public function getMatchNotes()
    {
        return $this->notes;
    }

    /**
     * @return array[tx_cfcleague_models_MatchNote]
     */
    public function getMatchNotesHome()
    {
        return $this->notesHome['_all'];
    }

    /**
     * @return array[tx_cfcleague_models_MatchNote]
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
     * @return array[tx_cfcleague_models_MatchNote]
     */
    public function getMatchNotes4Profile($profileUid)
    {
        if (array_key_exists($profileUid, $this->notesHome)) {
            return $this->notesHome[$profileUid];
        }
        if (array_key_exists($profileUid, $this->notesGuest)) {
            return $this->notesGuest[$profileUid];
        }

        return array();
    }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3sportstats/util/class.tx_t3sportstats_util_MatchNoteProvider.php']) {
    include_once $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3sportstats/util/class.tx_t3sportstats_util_MatchNoteProvider.php'];
}
