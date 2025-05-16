<?php

namespace System25\T3sports\Hooks;

use Exception;
use Sys25\RnBase\Configuration\ConfigurationInterface;
use Sys25\RnBase\Frontend\Filter\BaseFilter;
use Sys25\RnBase\Frontend\Marker\Templates;
use Sys25\RnBase\Frontend\Request\Request;
use System25\T3sports\Frontend\Marker\PlayerStatsMarker;
use System25\T3sports\Frontend\Marker\ProfileMarker;
use System25\T3sports\Model\Profile;
use System25\T3sports\Service\Statistics;
use tx_rnbase;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2008-2023 Rene Nitzsche
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
 * Extend marker classes.
 *
 * @author Rene Nitzsche
 */
class Marker
{
    public static $filterData = [
        'player' => [
            'tableAlias' => 'PLAYERSTAT',
            'colName' => 'player',
            'search' => 'searchPlayerStats',
        ],
        'coach' => [
            'tableAlias' => 'COACHSTAT',
            'colName' => 'coach',
            'search' => 'searchCoachStats',
        ],
        'referee' => [
            'tableAlias' => 'REFEREESTAT',
            'colName' => 'referee',
            'search' => 'searchRefereeStats',
        ],
    ];

    private $statsSrv;

    public function __construct(?Statistics $statisticsService = null)
    {
        $this->statsSrv = $statisticsService ?: new Statistics();
    }

    /**
     * Extend profileMarker for statistical data about profile.
     *
     * @param array $params
     * @param ProfileMarker $parent
     */
    public function parseProfile($params, $parent)
    {
        // Wir benötigen mehrere Statistiken pro Person
        // Diese müssen per TS konfiguriert werden
        // stats.liga.fields..
        // Marker: ###PROFILE_STATS_LIGA###
        $config = $params['conf'];
        $confId = $params['confId'].'stats.';
        $profile = $params['item'];
        $template = $params['template'];
        $markerPrefix = $params['marker'];

        $subpartArray = [];
        $statKeys = $config->getKeyNames($confId);
        foreach ($statKeys as $statKey) {
            // Die Daten holen
            $subpartMarker = $markerPrefix.'_STATS_'.strtoupper($statKey);

            $subpart = Templates::getSubpart($template, '###'.$subpartMarker.'###');
            if (!$subpart) {
                continue;
            }
            $items = $this->findData($profile, $config, $confId, $statKey);
            // Markerklasse aus Config holen
            $markerClass = $config->get($confId.$statKey.'.markerClass');
            $markerClass = $markerClass ? $markerClass : PlayerStatsMarker::class;
            $marker = tx_rnbase::makeInstance($markerClass);
            // Wir sollten nur einen Datensatz haben und können diesen jetzt ausgeben
            $subpartArray['###'.$subpartMarker.'###'] = $marker->parseTemplate($subpart, $items[0] ?? null, $config->getFormatter(), $confId.$statKey.'.data.', $subpartMarker);
        }

        $params['template'] = Templates::substituteMarkerArrayCached($template, [], $subpartArray);
    }

    /**
     * @param Profile $profile
     * @param ConfigurationInterface $configurations
     * @param string $confId
     * @param string $type
     *
     * @return array
     * @throws Exception
     */
    private function findData(Profile $profile, ConfigurationInterface $configurations, $confId, $type)
    {
        $request = new Request($configurations->getParameters(), $configurations, $confId);
        $confId = $confId.$type.'.';
        $filter = BaseFilter::createFilter(
            $request,
            //             new \ArrayObject(),
            //             $configurations,
            //             new \ArrayObject(),
            $confId
        );

        $fields = [];
        $filterType = $configurations->get($confId.'filterType');
        if (!$filterType) {
            throw new Exception('t3sportstats: No filter type configured in '.$confId.'filterType');
        }
        $filterType = strtolower($filterType);
        $fields[self::$filterData[$filterType]['tableAlias'].'.'.self::$filterData[$filterType]['colName']][OP_EQ_INT] = $profile->getUid();
        $options = [
            'enablefieldsoff' => 1,
        ];
        // $options['debug'] = 1;
        $filter->init($fields, $options);

        $searchMethod = self::$filterData[$filterType]['search'];
        $items = $this->statsSrv->$searchMethod($fields, $options);

        return $items;
    }
}
