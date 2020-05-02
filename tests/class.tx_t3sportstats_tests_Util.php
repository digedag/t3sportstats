<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008-2017 Rene Nitzsche (rene@system25.de)
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

require_once tx_rnbase_util_Extensions::extPath('rn_base').'class.tx_rnbase.php';

tx_rnbase::load('tx_rnbase_util_Spyc');

class tx_t3sportstats_tests_Util
{
    public static function createCompetition($uid, $saison, $agegroup)
    {
        return new tx_cfcleague_models_Competition(array('uid' => $uid, 'saison' => $saison, 'agegroup' => $agegroup));
    }

    public static function getMatches()
    {
        $data = tx_rnbase_util_Spyc::YAMLLoad(self::getFixturePath('statistics.yaml'));
        $comps = self::makeInstances($data['league_1'], $data['league_1']['clazz']);

        $data = $data['league_1']['matches'];
        $matches = self::makeInstances($data, $data['clazz']);
        foreach ($matches as $match) {
            $match->setCompetition($comps[0]);
        }

        return $matches;
    }

    public static function getMatchNotes($matchIdx)
    {
        $data = tx_rnbase_util_Spyc::YAMLLoad(self::getFixturePath('statistics.yaml'));
        $data = $data['league_1']['matches'][$matchIdx]['matchnotes'];
        $notes = self::makeInstances($data, $data['clazz']);

        return $notes;
    }

    private static function makeInstances($yamlData, $clazzName)
    {
        // Sicherstellen, daß die Klasse geladen wurde
        $ret = array();
        tx_rnbase::load($clazzName);
        foreach ($yamlData as $key => $arr) {
            if (isset($arr['record']) && is_array($arr['record'])) {
                $ret[$key] = new $clazzName($arr['record']);
            }
        }

        return $ret;
    }

    private static function getFixturePath($filename)
    {
        return tx_rnbase_util_Extensions::extPath('t3sportstats').'tests/fixtures/'.$filename;
    }
}
