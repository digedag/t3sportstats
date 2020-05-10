<?php

namespace System25\T3sports\Tests\StatsIndexer;

use System25\T3sports\Utility\StatsMatchNoteProvider;
use System25\T3sports\Tests\StatsFixtureUtil;
use System25\T3sports\StatsIndexer\PlayerStats;
use System25\T3sports\Service\Statistics;
use System25\T3sports\Sports\ServiceLocator;
use System25\T3sports\Sports\Football;

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

class PlayerStatsTest extends \tx_rnbase_tests_BaseTestCase
{
    private $statsService;

    private $serviceLocator;

    public function setUp()
    {
        $this->statsService = new Statistics();
        $this->serviceLocator = $this->prophesize(ServiceLocator::class);

        \System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('goals', '10,11,12,13');
        \System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('assists', '31');
        \System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('goalshead', '11');
        \System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('goalspenalty', '12');
        \System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('goalsown', '30');
        \System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('cardyellow', '70');
        \System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('cardyr', '71');
        \System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('cardred', '72');
        \System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('changeout', '80');
        \System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('changein', '81');
        \System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('captain', '200');
    }

    /**
     * @group unit
     */
    public function testIndexPlayerStats()
    {
        $this->serviceLocator->getSportsService('football')
            ->willReturn(new Football());
        $matchIdx = 0;
        $matches = StatsFixtureUtil::getMatches();

        $match = $matches[$matchIdx];
        $bagHash = [];
        $bags = $this->statsService->getPlayerBags($match, true);
        foreach ($bags as $bag) {
            $bagHash[$bag->getParentUid()] = $bag;
        }
        $bags = $this->statsService->getPlayerBags($match, false);
        foreach ($bags as $bag) {
            $bagHash[$bag->getParentUid()] = $bag;
        }
        $notes = StatsFixtureUtil::getMatchNotes($matchIdx);

        $mnProv = StatsMatchNoteProvider::createInstance($notes);
        $this->getService($this->serviceLocator->reveal())->indexPlayerStats($bagHash[100], $match, $mnProv, true);

        //		Tx_Rnbase_Utility_T3General::debug($bagHash[100], 'class.tx_t3sportstats_tests_srvPlayerStats_testcase.php'); // TODO: remove me
        $this->assertEquals(2, $bagHash[100]->getTypeValue('goals'), 'Goals count is wrong');
        $this->assertEquals(1, $bagHash[100]->getTypeValue('goalshead'), 'Goals header count is wrong');
        $this->assertEquals(1, $bagHash[100]->getTypeValue('win'), 'Win count is wrong');
        $this->assertEquals(0, $bagHash[100]->getTypeValue('loose'), 'Loose count is wrong');

        $this->getService()->indexPlayerStats($bagHash[110], $match, $mnProv, true);
        $this->assertEquals(1, $bagHash[110]->getTypeValue('changein'), 'Changein is wrong');
        $this->assertEquals(1, $bagHash[110]->getTypeValue('changein'), 'Changein is wrong');

        $this->getService()->indexPlayerStats($bagHash[102], $match, $mnProv, true);
        $this->assertEquals(1, $bagHash[102]->getTypeValue('changeout'), 'Changeout is wrong');

        $this->getService()->indexPlayerStats($bagHash[200], $match, $mnProv, false);
        $this->assertEquals(0, $bagHash[200]->getTypeValue('win'), 'Win count is wrong');
        $this->assertEquals(1, $bagHash[200]->getTypeValue('loose'), 'Loose count is wrong');
    }

    /**
     * @return PlayerStats
     */
    private static function getService($arg = null)
    {
        return \tx_rnbase::makeInstance(PlayerStats::class, $arg);
    }
}
