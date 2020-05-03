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

class tx_t3sportstats_tests_srvPlayerStats_testcase extends tx_phpunit_testcase
{
    public function test_indexPlayerStats()
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
        $bags = $srv->getPlayerBags($match, false);
        foreach ($bags as $bag) {
            $bagHash[$bag->getParentUid()] = $bag;
        }
        $notes = tx_t3sportstats_tests_Util::getMatchNotes($matchIdx);

        $mnProv = StatsMatchNoteProvider::createInstance($notes);
        $this->getService()->indexPlayerStats($bagHash[100], $match, $mnProv, true);

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

    public function testGetInstance()
    {
        $this->assertTrue(is_object(tx_rnbase_util_Misc::getService('t3sportsPlayerStats', 'base')), 'Service not registered.');
    }

    /**
     * @return tx_t3sportstats_srv_PlayerStats
     */
    private static function getService()
    {
        return tx_rnbase::makeInstance('tx_t3sportstats_srv_PlayerStats');
    }
}
