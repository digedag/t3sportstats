<?php

namespace System25\T3sports\Tests;

use Sys25\RnBase\Utility\Spyc;
use System25\T3sports\Model\Competition;
use System25\T3sports\Model\Fixture;

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

class StatsFixtureUtil
{
    public static function createCompetition($uid, $saison, $agegroup)
    {
        return new Competition(['uid' => $uid, 'saison' => $saison, 'agegroup' => $agegroup]);
    }

    public static function getMatches()
    {
        $data = Spyc::YAMLLoad(self::getFixturePath('statistics.yaml'));

        //        $league = Competition::getCompetitionInstance($data['record']['uid'], $data['record']);
        $league = Competition::getCompetitionInstance($data['league_1'][0]['record']['uid'], $data['league_1'][0]['record']);

        //        $comps = self::makeInstances($data['league_1'], $data['league_1']['clazz']);
        $teamData = $data['league_1']['teams'];
        $teams = [];
        foreach (self::makeInstances($teamData, $teamData['clazz']) as $team) {
            $teams[$team->getUid()] = $team;
        }
        $data = $data['league_1']['matches'];
        $matches = self::makeInstances($data, $data['clazz']);
        foreach ($matches as $match) {
            /* @var Fixture $match*/
            $match->setHome($teams[$match->getProperty('home')]);
            $match->setGuest($teams[$match->getProperty('guest')]);
        }

        return $matches;
    }

    public static function getMatchNotes($matchIdx)
    {
        $data = Spyc::YAMLLoad(self::getFixturePath('statistics.yaml'));
        $data = $data['league_1']['matches'][$matchIdx]['matchnotes'];
        $notes = self::makeInstances($data, $data['clazz']);

        return $notes;
    }

    private static function makeInstances($yamlData, $clazzName)
    {
        // Sicherstellen, daß die Klasse geladen wurde
        $ret = [];
        foreach ($yamlData as $key => $arr) {
            if (isset($arr['record']) && is_array($arr['record'])) {
                $ret[$key] = new $clazzName($arr['record']);
            }
        }

        return $ret;
    }

    private static function getFixturePath($filename)
    {
        return __DIR__.'/../../fixtures/'.$filename;
    }
}
