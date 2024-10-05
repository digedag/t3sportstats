<?php

namespace System25\T3sports\Utility;

use Sys25\RnBase\Utility\Strings;
use System25\T3sports\Repository\SeriesRepository;
use tx_rnbase;

/***************************************************************
*  Copyright notice
*
*  (c) 2010-2024 Rene Nitzsche (rene@system25.de)
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

class StatsConfig
{
    private $seriesRepo;
    public function __construct(?SeriesRepository $seriesRepo = null)
    {
        $this->seriesRepo = $seriesRepo ?: tx_rnbase::makeInstance(SeriesRepository::class);
    }

    public function lookupSeries($config)
    {
        $seriesList = $this->seriesRepo->findAll();
        foreach ($seriesList as $series) {
            $config['items'][] = [$series->getName(), $series->getUid()];
        }

        return $config;
    }

    /**
     * Returns all configured statistics type for flexform.
     *
     * @return array
     */
    public static function lookupPlayerStatsReport($config)
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['playerStats']['reports'])) {
            $types = Strings::trimExplode(',', $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['playerStats']['reports']);
            foreach ($types as $type) {
                $config['items'][] = [$type, $type];
            }
        }

        return $config;
    }

    /**
     * Returns all configured statistics type for flexform.
     *
     * @return array
     */
    public static function lookupCoachStatsReport($config)
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['coachStats']['reports'])) {
            $types = Strings::trimExplode(',', $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['coachStats']['reports']);
            foreach ($types as $type) {
                $config['items'][] = [$type, $type];
            }
        }

        return $config;
    }

    /**
     * Returns all configured statistics type for flexform.
     *
     * @return array
     */
    public static function lookupRefereeStatsReport($config)
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['refereeStats']['reports'])) {
            $types = Strings::trimExplode(',', $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['refereeStats']['reports']);
            foreach ($types as $type) {
                $config['items'][] = [$type, $type];
            }
        }

        return $config;
    }

    public static function registerPlayerStatsReport($statsType)
    {
        self::registerStatsReport('playerStats', $statsType);
    }

    public static function registerCoachStatsReport($statsType)
    {
        self::registerStatsReport('coachStats', $statsType);
    }

    public static function registerRefereeStatsReport($statsType)
    {
        self::registerStatsReport('refereeStats', $statsType);
    }

    private static function registerStatsReport($baseType, $statsType)
    {
        $current = [];
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats'][$baseType]['reports'])) {
            $current = array_flip(Strings::trimExplode(',', $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats'][$baseType]['reports']));
        }
        if (!array_key_exists($statsType, $current)) {
            $current = array_flip($current);
            $current[] = $statsType;
            $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats'][$baseType]['reports'] = implode(',', $current);
        }
    }

    /**
     * Register a new simple statistics.
     *
     * @param string $column
     * @param mixed $types commaseparated list of match event uids
     */
    public static function registerPlayerStatsSimple($column, $types)
    {
        self::registerStatsSimple('playerStats', $column, $types);
    }

    /**
     * Register a new simple statistics.
     *
     * @param string $column
     * @param mixed $types commaseparated list of match event uids
     */
    public static function registerCoachStatsSimple($column, $types)
    {
        self::registerStatsSimple('coachStats', $column, $types);
    }

    /**
     * Register a new simple statistics.
     *
     * @param string $column
     * @param mixed $types commaseparated list of match event uids
     */
    public static function registerRefereeStatsSimple($column, $types)
    {
        self::registerStatsSimple('refereeStats', $column, $types);
    }

    /**
     * Register a new simple statistics.
     *
     * @param string $column
     * @param mixed $types commaseparated list of match event uids
     */
    private static function registerStatsSimple($type, $column, $types)
    {
        $column = strtolower($column);
        if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats'][$type]['simpleStats'])) {
            $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats'][$type]['simpleStats'] = [];
        }

        if (!array_key_exists($column, $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats'][$type]['simpleStats'])) {
            $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats'][$type]['simpleStats'][$column] = [
                'types' => $types,
            ];
        } else {
            $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats'][$type]['simpleStats'][$column]['types'] .= ','.$types;
        }
    }

    /**
     * Returns all registered simple statistics.
     *
     * @return array
     */
    public static function getPlayerStatsSimple()
    {
        return $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['playerStats']['simpleStats'] ?? [];
    }

    /**
     * Returns all registered simple statistics for coaches.
     *
     * @return array
     */
    public static function getCoachStatsSimple()
    {
        return $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['coachStats']['simpleStats'] ?? [];
    }

    /**
     * Returns all registered simple statistics for referees.
     *
     * @return array
     */
    public static function getRefereeStatsSimple()
    {
        return $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['refereeStats']['simpleStats'] ?? [];
    }
}
