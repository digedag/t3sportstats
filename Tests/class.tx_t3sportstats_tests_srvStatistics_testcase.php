<?php

use System25\T3sports\Tests\StatsFixtureUtil;
use System25\T3sports\Service\StatsServiceRegistry;
use System25\T3sports\Service\Statistics;

/***************************************************************
*  Copyright notice
*
*  (c) 2008-2010 Rene Nitzsche (rene@system25.de)
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

class tx_t3sportstats_tests_srvStatistics_testcase extends \tx_phpunit_testcase
{
    public function test_serviceStatistics()
    {
        $this->assertTrue(StatsServiceRegistry::getStatisticService() instanceof Statistics, 'Service not found!');
    }

    public function test_lookupPlayerServices()
    {
        $srv = StatsServiceRegistry::getStatisticService();
        $srvArr = $srv->lookupPlayerServices();
        $this->assertTrue(is_array($srvArr), 'Player Services not found!');
        $this->assertTrue(count($srvArr) > 0, 'Player Services not found!');
    }

    public function test_getPlayerBags()
    {
        $matches = StatsFixtureUtil::getMatches();
        $srv = StatsServiceRegistry::getStatisticService();
        $bags = $srv->getPlayerBags($matches[0], true);
        $this->assertEquals(7, count($bags), 'Number of databags is wrong.');

        $bags = $srv->getPlayerBags($matches[0], false);
        $this->assertEquals(8, count($bags), 'Number of databags is wrong.');
    }
}
