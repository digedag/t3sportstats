<?php

namespace System25\T3sports\Tests\Utility;

use System25\T3sports\Utility\StatsMatchNoteProvider;
use System25\T3sports\Tests\StatsFixtureUtil;

/***************************************************************
*  Copyright notice
*
*  (c) 2008-2020 Rene Nitzsche (rene@system25.de)
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

class StatsMatchNoteProviderTest extends \tx_rnbase_tests_BaseTestCase
{
    /**
     * @group unit
     */
    public function test_getMatchNotes4Profile()
    {
        $matchIdx = 0;
        $notes = StatsFixtureUtil::getMatchNotes($matchIdx);
        $mnProv = StatsMatchNoteProvider::createInstance($notes);
        $notes = $mnProv->getMatchNotes4Profile(100);
        $this->assertEquals(3, count($notes), 'Number of notes for player is wrong');
        $notes = $mnProv->getMatchNotes4Profile(202);
        $this->assertEquals(1, count($notes), 'Number of notes for player is wrong');
        $notes = $mnProv->getMatchNotes4Profile(110);
        $this->assertEquals(2, count($notes), 'Number of notes for player is wrong');
    }
}
