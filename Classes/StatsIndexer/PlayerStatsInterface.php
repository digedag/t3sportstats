<?php

namespace System25\T3sports\StatsIndexer;

use System25\T3sports\Model\Fixture;
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
interface PlayerStatsInterface extends StatsInterface
{
    public const INDEXER_TYPE = 'player';

    /**
     * Update statistics for a player.
     *
     * @param StatsDataBag $dataBag
     * @param Fixture $match
     * @param StatsMatchNoteProvider $mnProv
     * @param bool $isHome
     *
     * @return void
     */
    public function indexPlayerStats(StatsDataBag $dataBag, Fixture $match, StatsMatchNoteProvider $mnProv, bool $isHome);
}
