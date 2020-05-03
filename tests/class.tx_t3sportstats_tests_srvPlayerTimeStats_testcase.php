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

class tx_t3sportstats_tests_srvPlayerTimeStats_testcase extends tx_phpunit_testcase
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

        $this->assertEquals(90, $bagHash[100]->getTypeValue('playtime'), 'Playtime is wrong');

        $this->getService()->indexPlayerStats($bagHash[110], $match, $mnProv, true);
        $this->assertEquals(42, $bagHash[110]->getTypeValue('playtime'), 'Playtime is wrong');

        $this->getService()->indexPlayerStats($bagHash[102], $match, $mnProv, true);
        $this->assertEquals(48, $bagHash[102]->getTypeValue('playtime'), 'Playtime is wrong');
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
        $this->assertEquals(90, $bagHash[202]->getTypeValue('playtime'), 'Playtime is wrong');
        $this->assertEquals(1, $bagHash[202]->getTypeValue('played'), 'Played is wrong');

        $this->getService()->indexPlayerStats($bagHash[204], $match, $mnProv, false);
        $this->assertEquals(89, $bagHash[204]->getTypeValue('playtime'), 'Playtime is wrong');
        $this->assertEquals(1, $bagHash[204]->getTypeValue('played'), 'Played is wrong');

        $this->getService()->indexPlayerStats($bagHash[201], $match, $mnProv, false);
        $this->assertEquals(65, $bagHash[201]->getTypeValue('playtime'), 'Playtime is wrong');
        $this->assertEquals(1, $bagHash[201]->getTypeValue('played'), 'Played is wrong');

        $this->getService()->indexPlayerStats($bagHash[220], $match, $mnProv, false);
        $this->assertEquals(10, $bagHash[220]->getTypeValue('playtime'), 'Playtime is wrong');
        $this->assertEquals(1, $bagHash[220]->getTypeValue('played'), 'Played is wrong');

        $this->getService()->indexPlayerStats($bagHash[200], $match, $mnProv, false);
        $this->assertEquals(80, $bagHash[200]->getTypeValue('playtime'), 'Playtime is wrong');
        $this->assertEquals(1, $bagHash[200]->getTypeValue('played'), 'Played is wrong');
    }

    public function testGetInstance()
    {
        $this->assertTrue(is_object(tx_rnbase_util_Misc::getService('t3sportsPlayerStats', 'playtime')), 'Service not registered.');
    }

    /**
     * @return tx_t3sportstats_srv_PlayerTimeStats
     */
    private static function getService()
    {
        return tx_rnbase::makeInstance('tx_t3sportstats_srv_PlayerTimeStats');
    }
}
