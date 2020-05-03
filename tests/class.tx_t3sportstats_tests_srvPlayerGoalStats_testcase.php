<?php

use System25\T3sports\Utility\StatsMatchNoteProvider;

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

class tx_t3sportstats_tests_srvPlayerGoalStats_testcase extends tx_phpunit_testcase
{
    public function test_indexPlayerStatsHome()
    {
        $matchIdx = 0;
        $matches = tx_t3sportstats_tests_Util::getMatches();

        $match = $matches[$matchIdx];
        $srv = tx_t3sportstats_util_ServiceRegistry::getStatisticService();
        $bagHash = array();
        $bags = $srv->getPlayerBags($match, true);
        foreach ($bags as $bag) {
            $bagHash[$bag->getParentUid()] = $bag;
        }
        $notes = tx_t3sportstats_tests_Util::getMatchNotes($matchIdx);

        $mnProv = StatsMatchNoteProvider::createInstance($notes);

        $this->getService()->indexPlayerStats($bagHash[100], $match, $mnProv, true);
        $this->assertEquals(2, $bagHash[100]->getTypeValue('goalshome'), 'Goals home are wrong');
        $this->assertEquals(0, $bagHash[100]->getTypeValue('goalsaway'), 'Goals away are wrong');
        $this->assertEquals(0, $bagHash[100]->getTypeValue('goalsjoker'), 'Goals joker are wrong');

        $this->getService()->indexPlayerStats($bagHash[101], $match, $mnProv, true);
        $this->assertEquals(0, $bagHash[101]->getTypeValue('goalshome'), 'Goals home are wrong');
        $this->assertEquals(0, $bagHash[101]->getTypeValue('goalsaway'), 'Goals away are wrong');
        $this->assertEquals(0, $bagHash[101]->getTypeValue('goalsjoker'), 'Goals joker are wrong');

        $this->getService()->indexPlayerStats($bagHash[110], $match, $mnProv, true);
        $this->assertEquals(1, $bagHash[110]->getTypeValue('goalshome'), 'Goals home are wrong');
        $this->assertEquals(0, $bagHash[110]->getTypeValue('goalsaway'), 'Goals away are wrong');
        $this->assertEquals(1, $bagHash[110]->getTypeValue('goalsjoker'), 'Goals joker are wrong');
    }

    public function test_indexPlayerStatsGuest()
    {
        $matchIdx = 0;
        $matches = tx_t3sportstats_tests_Util::getMatches();

        $match = $matches[$matchIdx];
        $srv = tx_t3sportstats_util_ServiceRegistry::getStatisticService();
        $bagHash = array();
        $bags = $srv->getPlayerBags($match, false);
        foreach ($bags as $bag) {
            $bagHash[$bag->getParentUid()] = $bag;
        }
        $notes = tx_t3sportstats_tests_Util::getMatchNotes($matchIdx);

        $mnProv = StatsMatchNoteProvider::createInstance($notes);

        $this->getService()->indexPlayerStats($bagHash[202], $match, $mnProv, false);
        $this->assertEquals(0, $bagHash[202]->getTypeValue('goalshome'), 'Goals home are wrong');
        $this->assertEquals(1, $bagHash[202]->getTypeValue('goalsaway'), 'Goals away are wrong');
        $this->assertEquals(0, $bagHash[202]->getTypeValue('goalsjoker'), 'Goals joker are wrong');
    }

    public function testGetInstance()
    {
        $this->assertTrue(is_object(tx_rnbase_util_Misc::getService('t3sportsPlayerStats', 'goals')), 'Service not registered.');
    }

    /**
     * @return tx_t3sportstats_srv_PlayerTimeStats
     */
    private static function getService()
    {
        return tx_rnbase::makeInstance('tx_t3sportstats_srv_PlayerGoalStats');
    }
}
