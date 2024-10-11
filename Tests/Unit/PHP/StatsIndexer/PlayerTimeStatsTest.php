<?php

namespace System25\T3sports\Tests\StatsIndexer;

use PHPUnit\Framework\Assert;
use Prophecy\PhpUnit\ProphecyTrait;
use Sys25\RnBase\Testing\BaseTestCase;
use System25\T3sports\Service\Statistics;
use System25\T3sports\Sports\Football;
use System25\T3sports\Sports\ServiceLocator;
use System25\T3sports\StatsIndexer\PlayerTimeStats;
use System25\T3sports\Tests\StatsFixtureUtil;
use System25\T3sports\Utility\StatsMatchNoteProvider;
use tx_rnbase;

/***************************************************************
*  Copyright notice
*
*  (c) 2008-2024 Rene Nitzsche (rene@system25.de)
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

class PlayerTimeStatsTest extends BaseTestCase
{
    use ProphecyTrait;

    private $statsService;

    private $serviceLocator;

    public function setUp(): void
    {
        $this->statsService = new Statistics();
        $this->serviceLocator = $this->prophesize(ServiceLocator::class);
        $this->serviceLocator->getSportsService('football')
            ->willReturn(new Football());

        \System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('goals', '10,11,12,13');
        \System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('assists', '31');
    }

    /**
     * @group unit
     */
    public function testIndexPlayerStatsHome()
    {
        $matchIdx = 0;
        $matches = StatsFixtureUtil::getMatches();

        $match = $matches[$matchIdx];
        $bagHash = [];
        $bags = $this->statsService->getPlayerBags($match, true);
        foreach ($bags as $bag) {
            $bagHash[$bag->getParentUid()] = $bag;
        }
        $notes = StatsFixtureUtil::getMatchNotes($matchIdx);

        $mnProv = StatsMatchNoteProvider::createInstance($notes);

        $statsService = $this->getService($this->serviceLocator->reveal());
        $statsService->indexPlayerStats($bagHash[100], $match, $mnProv, true);

        Assert::assertEquals(90, $bagHash[100]->getTypeValue('playtime'), 'Playtime is wrong');

        $statsService->indexPlayerStats($bagHash[110], $match, $mnProv, true);
        Assert::assertEquals(42, $bagHash[110]->getTypeValue('playtime'), 'Playtime is wrong');

        $statsService->indexPlayerStats($bagHash[102], $match, $mnProv, true);
        Assert::assertEquals(48, $bagHash[102]->getTypeValue('playtime'), 'Playtime is wrong');
    }

    /**
     * @group unit
     */
    public function testIndexPlayerStatsGuest()
    {
        $matchIdx = 0;
        $matches = StatsFixtureUtil::getMatches();

        $match = $matches[$matchIdx];
        $bagHash = [];
        $bags = $this->statsService->getPlayerBags($match, false);
        foreach ($bags as $bag) {
            $bagHash[$bag->getParentUid()] = $bag;
        }
        $notes = StatsFixtureUtil::getMatchNotes($matchIdx);

        $mnProv = StatsMatchNoteProvider::createInstance($notes);

        $statsService = $this->getService($this->serviceLocator->reveal());
        $statsService->indexPlayerStats($bagHash[202], $match, $mnProv, false);
        Assert::assertEquals(90, $bagHash[202]->getTypeValue('playtime'), 'Playtime is wrong');
        Assert::assertEquals(1, $bagHash[202]->getTypeValue('played'), 'Played is wrong');

        $statsService->indexPlayerStats($bagHash[204], $match, $mnProv, false);
        Assert::assertEquals(89, $bagHash[204]->getTypeValue('playtime'), 'Playtime is wrong');
        Assert::assertEquals(1, $bagHash[204]->getTypeValue('played'), 'Played is wrong');

        $statsService->indexPlayerStats($bagHash[201], $match, $mnProv, false);
        Assert::assertEquals(65, $bagHash[201]->getTypeValue('playtime'), 'Playtime is wrong');
        Assert::assertEquals(1, $bagHash[201]->getTypeValue('played'), 'Played is wrong');

        $statsService->indexPlayerStats($bagHash[220], $match, $mnProv, false);
        Assert::assertEquals(10, $bagHash[220]->getTypeValue('playtime'), 'Playtime is wrong');
        Assert::assertEquals(1, $bagHash[220]->getTypeValue('played'), 'Played is wrong');

        $statsService->indexPlayerStats($bagHash[200], $match, $mnProv, false);
        Assert::assertEquals(80, $bagHash[200]->getTypeValue('playtime'), 'Playtime is wrong');
        Assert::assertEquals(1, $bagHash[200]->getTypeValue('played'), 'Played is wrong');
    }

    /**
     * @return PlayerTimeStats
     */
    private static function getService($arg = null)
    {
        return tx_rnbase::makeInstance(PlayerTimeStats::class, $arg);
    }
}
