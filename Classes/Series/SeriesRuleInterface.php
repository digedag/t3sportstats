<?php

namespace System25\T3sports\Series;

use System25\T3sports\Model\Club;
use System25\T3sports\Model\Fixture;
use System25\T3sports\Model\Team;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2024 Rene Nitzsche (rene@system25.de)
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
 * Interface for series rule
 *
 */
interface SeriesRuleInterface
{
    const TAG = 't3sports.stats.seriesrule';

    function getAlias(): string;
    function getTcaLabel(): string;
    function isSatified(Fixture $match, bool $isHome, $config): bool;
}
