<?php

namespace System25\T3sports\Model;

use Sys25\RnBase\Domain\Model\BaseModel;
use Tx_Rnbase_Domain_Model_Base;

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
 * Model for a player stats record.
 */
class PlayerStat extends BaseModel
{
    public function getTableName()
    {
        return 'tx_t3sportstats_players';
    }

    /**
     * Returns the competition uid.
     *
     * @return int
     */
    public function getCompetitionUid()
    {
        return $this->getProperty('competition');
    }

    public function getMatchUid()
    {
        return $this->getProperty('t3match');
    }

    /**
     * Returns the club.
     *
     * @return int
     */
    public function getClubUid()
    {
        return $this->getProperty('club');
    }

    /**
     * Returns the opponent club uid.
     *
     * @return int
     */
    public function getClubOppUid()
    {
        return $this->getProperty('clubopp');
    }

    /**
     * Returns the player uid.
     *
     * @return int
     */
    public function getPlayerUid()
    {
        return $this->getProperty('player');
    }
}
