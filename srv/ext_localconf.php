<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

tx_rnbase_util_Extensions::addService(
    $_EXTKEY,
    't3sportstats' /* sv type */,
    'tx_t3sportstats_srv_Statistics' /* sv key */,
    array(
    'title' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db:service_t3sports_statistics_title', 'description' => 'Statistical data about T3sports', 'subtype' => 'statistics',
    'available' => true, 'priority' => 50, 'quality' => 50,
    'os' => '', 'exec' => '',
    'classFile' => tx_rnbase_util_Extensions::extPath($_EXTKEY).'srv/class.tx_t3sportstats_srv_Statistics.php',
    'className' => 'tx_t3sportstats_srv_Statistics',
  )
);

tx_rnbase_util_Extensions::addService(
    $_EXTKEY,
    't3sportsPlayerStats' /* sv type */,
    'tx_t3sportstats_srv_PlayerStats' /* sv key */,
    array(
    'title' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db:service_t3sports_playerstats_title', 'description' => 'Statistical data about players', 'subtype' => 'base',
    'available' => true, 'priority' => 50, 'quality' => 50,
    'os' => '', 'exec' => '',
    'classFile' => tx_rnbase_util_Extensions::extPath($_EXTKEY).'srv/class.tx_t3sportstats_srv_PlayerStats.php',
    'className' => 'tx_t3sportstats_srv_PlayerStats',
  )
);

tx_rnbase_util_Extensions::addService(
    $_EXTKEY,
    't3sportsPlayerStats' /* sv type */,
    'tx_t3sportstats_srv_PlayerTimeStats' /* sv key */,
    array(
    'title' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db:service_t3sports_playertimestats_title', 'description' => 'Statistical data about players', 'subtype' => 'playtime',
    'available' => true, 'priority' => 50, 'quality' => 50,
    'os' => '', 'exec' => '',
    'classFile' => tx_rnbase_util_Extensions::extPath($_EXTKEY).'srv/class.tx_t3sportstats_srv_PlayerTimeStats.php',
    'className' => 'tx_t3sportstats_srv_PlayerTimeStats',
  )
);

tx_rnbase_util_Extensions::addService(
    $_EXTKEY,
    't3sportsPlayerStats' /* sv type */,
    'tx_t3sportstats_srv_PlayerGoalStats' /* sv key */,
    array(
    'title' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db:service_t3sports_playertimestats_title', 'description' => 'Statistical data about players', 'subtype' => 'goals',
    'available' => true, 'priority' => 50, 'quality' => 50,
    'os' => '', 'exec' => '',
    'classFile' => tx_rnbase_util_Extensions::extPath($_EXTKEY).'srv/class.tx_t3sportstats_srv_PlayerGoalStats.php',
    'className' => 'tx_t3sportstats_srv_PlayerGoalStats',
  )
);

tx_rnbase_util_Extensions::addService(
    $_EXTKEY,
    't3sportsCoachStats' /* sv type */,
    'tx_t3sportstats_srv_CoachStats' /* sv key */,
    array(
    'title' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db:service_t3sports_playerstats_title', 'description' => 'Statistical data about coaches', 'subtype' => 'base',
    'available' => true, 'priority' => 50, 'quality' => 50,
    'os' => '', 'exec' => '',
    'classFile' => tx_rnbase_util_Extensions::extPath($_EXTKEY).'srv/class.tx_t3sportstats_srv_CoachStats.php',
    'className' => 'tx_t3sportstats_srv_CoachStats',
  )
);

tx_rnbase_util_Extensions::addService(
    $_EXTKEY,
    't3sportsRefereeStats' /* sv type */,
    'tx_t3sportstats_srv_RefereeStats' /* sv key */,
    [
        'title' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db:service_t3sports_playerstats_title', 'description' => 'Statistical data about referees', 'subtype' => 'base',
        'available' => true, 'priority' => 50, 'quality' => 50,
        'os' => '', 'exec' => '',
        'classFile' => tx_rnbase_util_Extensions::extPath($_EXTKEY).'srv/class.tx_t3sportstats_srv_RefereeStats.php',
        'className' => 'tx_t3sportstats_srv_RefereeStats',
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
