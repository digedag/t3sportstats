<?php

namespace System25\T3sports\Hooks;

use tx_rnbase_util_SearchBase;
use Tx_Rnbase_Utility_Strings;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2020 Rene Nitzsche
 *  Contact: rene@system25.de
 *  All rights reserved
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 ***************************************************************/

/**
 * Make additional fields for match filter.
 *
 * @author Rene Nitzsche
 */
class Filter
{
    private static $tableData = [
        'player' => [
            'tableAlias' => 'PLAYERSTAT',
            'colName' => 'player',
        ],
        'coach' => [
            'tableAlias' => 'COACHSTAT',
            'colName' => 'coach',
        ],
        'referee' => [
            'tableAlias' => 'REFEREESTAT',
            'colName' => 'referee',
        ],
    ];

    public function handleMatchFilter($params, $parent)
    {
        $configurations = $params['configurations'];
        $confId = $params['confid'];

        $parameters = $configurations->getParameters();
        $statsType = $parameters->get('statstype');
        if (!$statsType) { // Ist was per TS konfiguriert
            $statsType = $configurations->get($confId.'filter.statsType');
        }
        if (!$statsType) {
            return;
        }

        $statsKey = $parameters->get('statskey');
        if (!$statsKey) { // Ist was per TS konfiguriert
            $statsKey = $configurations->get($confId.'filter.statsKey');
        }

        $profileType = 'player';
        $profile = $parameters->getInt($profileType);
        if (!$profile) {
            $profileType = 'coach';
            $profile = $parameters->getInt($profileType);
        }
        if (!$profile) {
            $profileType = 'referee';
            $profile = $parameters->getInt($profileType);
        }
        if (!$profile) { // Ist was per TS konfiguriert
            $profileType = $configurations->get($confId.'filter.profileType');
            $profileParam = $configurations->get($confId.'filter.profileParam');
            if ($profileType && $profileParam) {
                $profile = $parameters->getInt($profileParam);
            }
        }

        if (!$profile) {
            return;
        }

        $fields = &$params['fields'];
        $confId .= 'filter.stats.'.$statsType.'.';

        $cols = $configurations->get($confId.'columns');
        if (!$cols) {
            return;
        }
        $cols = array_flip(Tx_Rnbase_Utility_Strings::trimExplode(',', $cols));

        if ($statsKey && array_key_exists(strtolower($statsKey), $cols)) {
            $fields[self::$tableData[$profileType]['tableAlias'].'.'.strtoupper($statsKey)][OP_GT_INT] = 0;
        } else {
            return;
        }

        // Ziel ist ein JOIN auf die playerstats, für den aktuellen Spieler und die aktuellen
        // fields der stats
        tx_rnbase_util_SearchBase::setConfigFields($fields, $configurations, $confId.'fields.');
        $fields[self::$tableData[$profileType]['tableAlias'].'.'.self::$tableData[$profileType]['colName']][OP_EQ_INT] = $profile;
        $parent->addFilterData($profileType, $profile);
    }
}
