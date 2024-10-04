<?php

namespace System25\T3sports\Tests\Series\Rule;

use PHPUnit\Framework\Assert;
use Sys25\RnBase\Testing\BaseTestCase;
use System25\T3sports\Model\Fixture;
use System25\T3sports\Series\Rule\WinRule;
use System25\T3sports\Series\SeriesRuleInterface;
use System25\T3sports\Tests\StatsFixtureUtil;

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

class WinTest extends BaseTestCase
{
    /** @var SeriesRuleInterface */
    private $rule;
    /** @var Fixture[] */
    private $winsHome;
    /** @var Fixture[] */
    private $winsAway;
    /** @var Fixture[] */
    private $draws;

    protected function setUp(): void
    {
        $this->rule = new WinRule();
        $fixtures = StatsFixtureUtil::getMatches();
        $this->winsHome = array_filter($fixtures, function (Fixture $f) { return $f->getGoalsHome() > $f->getGoalsGuest(); });
        $this->winsAway = array_filter($fixtures, function (Fixture $f) { return $f->getGoalsHome() < $f->getGoalsGuest(); });
        $this->draws = array_filter($fixtures, function (Fixture $f) { return $f->getGoalsHome() == $f->getGoalsGuest(); });
    }

    /**
     * @group unit
     */
    public function testIsSatisfiedWinHome()
    {
        $this->testIsSatisfied($this->winsHome, true, true);
        $this->testIsSatisfied($this->winsHome, false, false);
    }

    /**
     * @group unit
     */
    public function testIsSatisfiedWinAway()
    {
        $this->testIsSatisfied($this->winsAway, true, false);
        $this->testIsSatisfied($this->winsAway, false, true);
    }

    /**
     * @group unit
     */
    public function testIsSatisfiedDraw()
    {
        $this->testIsSatisfied($this->draws, true, false);
        $this->testIsSatisfied($this->draws, false, false);
    }

    private function testIsSatisfied(array $fixtures, $isHome, bool $expected)
    {
        $config = '';
        foreach ($fixtures as $fixture) {
            $result = $this->rule->isSatified($fixture, $isHome, $config);
            Assert::assertEquals($expected, $result);
        }
    }
}
