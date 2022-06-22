<?php

if (!defined('TYPO3_MODE')) {
    exit('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league']['clearStatistics_hook'][] = 'System25\T3sports\Hooks\ClearStats->clearStats4Comp';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league']['mergeProfiles_hook'][] = 'System25\T3sports\Hooks\MergeProfiles->mergeProfile';

// Hook for match search
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league']['search_Match_getTableMapping_hook'][] = 'System25\T3sports\Hooks\Search->getTableMappingMatch';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league']['search_Match_getJoins_hook'][] = 'System25\T3sports\Hooks\Search->getJoinsMatch';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league_fe']['search_Match_getTableMapping_hook'][] = 'System25\T3sports\Hooks\Search->getTableMappingMatch';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league_fe']['search_Match_getJoins_hook'][] = 'System25\T3sports\Hooks\Search->getJoinsMatch';

// Hook for profile marker
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league_fe']['profileMarker_afterSubst'][] = 'System25\T3sports\Hooks\Marker->parseProfile';

// Hook for match filter
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league_fe']['filterMatch_setfields'][] = 'System25\T3sports\Hooks\Filter->handleMatchFilter';

Sys25\RnBase\Utility\Extensions::addService(
    $_EXTKEY,
    't3sportstats' /* sv type */ ,
    'tx_t3sportstats_srv_Statistics' /* sv key */ ,
    [
        'title' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db:service_t3sports_statistics_title', 'description' => 'Statistical data about T3sports', 'subtype' => 'statistics',
        'available' => true, 'priority' => 50, 'quality' => 50,
        'os' => '', 'exec' => '',
        'className' => System25\T3sports\Service\Statistics::class,
    ]
);

Sys25\RnBase\Utility\Extensions::addService(
    $_EXTKEY,
    't3sportsPlayerStats' /* sv type */ ,
    'PlayerStats' /* sv key */ ,
    [
        'title' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db:service_t3sports_playerstats_title', 'description' => 'Statistical data about players', 'subtype' => 'base',
        'available' => true, 'priority' => 50, 'quality' => 50,
        'os' => '', 'exec' => '',
        'className' => System25\T3sports\StatsIndexer\PlayerStats::class,
    ]
);

Sys25\RnBase\Utility\Extensions::addService(
    $_EXTKEY,
    't3sportsPlayerStats' /* sv type */ ,
    'PlayerTimeStats' /* sv key */ ,
    [
        'title' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db:service_t3sports_playertimestats_title', 'description' => 'Statistical data about players', 'subtype' => 'playtime',
        'available' => true, 'priority' => 50, 'quality' => 50,
        'os' => '', 'exec' => '',
        'className' => System25\T3sports\StatsIndexer\PlayerTimeStats::class,
    ]
);

Sys25\RnBase\Utility\Extensions::addService(
    $_EXTKEY,
    't3sportsPlayerStats' /* sv type */ ,
    'PlayerGoalStats' /* sv key */ ,
    [
        'title' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db:service_t3sports_playertimestats_title', 'description' => 'Statistical data about players', 'subtype' => 'goals',
        'available' => true, 'priority' => 50, 'quality' => 50,
        'os' => '', 'exec' => '',
        'className' => System25\T3sports\StatsIndexer\PlayerGoalStats::class,
    ]
);

Sys25\RnBase\Utility\Extensions::addService(
    $_EXTKEY,
    't3sportsCoachStats' /* sv type */ ,
    'CoachStats' /* sv key */ ,
    [
        'title' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db:service_t3sports_playerstats_title', 'description' => 'Statistical data about coaches', 'subtype' => 'base',
        'available' => true, 'priority' => 50, 'quality' => 50,
        'os' => '', 'exec' => '',
        'className' => System25\T3sports\StatsIndexer\CoachStats::class,
    ]
);

Sys25\RnBase\Utility\Extensions::addService(
    $_EXTKEY,
    't3sportsRefereeStats' /* sv type */ ,
    'RefereeStats' /* sv key */ ,
    [
        'title' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db:service_t3sports_playerstats_title', 'description' => 'Statistical data about referees', 'subtype' => 'base',
        'available' => true, 'priority' => 50, 'quality' => 50,
        'os' => '', 'exec' => '',
        'className' => System25\T3sports\StatsIndexer\RefereeStats::class,
    ]
);

System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('goals', '10,11,12,13');
System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('assists', '31');
System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('goalshead', '11');
System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('goalspenalty', '12');
System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('goalsown', '30');
System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('cardyellow', '70');
System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('cardyr', '71');
System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('cardred', '72');
System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('changeout', '80');
System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('changein', '81');
System25\T3sports\Utility\StatsConfig::registerPlayerStatsSimple('captain', '200');

// Tore kommen über das Spielergebnis
//System25\T3sports\Utility\StatsConfig::registerCoachStatsSimple('goals', '10,11,12,13');
System25\T3sports\Utility\StatsConfig::registerCoachStatsSimple('cardyellow', '70');
System25\T3sports\Utility\StatsConfig::registerCoachStatsSimple('cardyr', '71');
System25\T3sports\Utility\StatsConfig::registerCoachStatsSimple('cardred', '72');
System25\T3sports\Utility\StatsConfig::registerCoachStatsSimple('changeout', '80');

System25\T3sports\Utility\StatsConfig::registerRefereeStatsSimple('goalspenalty', '12');
System25\T3sports\Utility\StatsConfig::registerRefereeStatsSimple('penalty', '12,32');
System25\T3sports\Utility\StatsConfig::registerRefereeStatsSimple('cardyellow', '70');
System25\T3sports\Utility\StatsConfig::registerRefereeStatsSimple('cardyr', '71');
System25\T3sports\Utility\StatsConfig::registerRefereeStatsSimple('cardred', '72');

// Register reports for plugin
System25\T3sports\Utility\StatsConfig::registerPlayerStatsReport('default');
System25\T3sports\Utility\StatsConfig::registerPlayerStatsReport('scorerlist');
System25\T3sports\Utility\StatsConfig::registerPlayerStatsReport('assistlist');

System25\T3sports\Utility\StatsConfig::registerCoachStatsReport('default');
System25\T3sports\Utility\StatsConfig::registerRefereeStatsReport('default');

// Register a new matchnote type
System25\T3sports\Utility\Misc::registerMatchNote('LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db:tx_cfcleague_match_notes.type.goalfreekick', '13');
