<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

tx_rnbase_util_Extensions::addService(
    $_EXTKEY,
    't3sportstats' /* sv type */,
    'tx_t3sportstats_srv_Statistics' /* sv key */,
    array(
    'title' => 'LLL:EXT:t3sportstats/locallang_db.xml:service_t3sports_statistics_title', 'description' => 'Statistical data about T3sports', 'subtype' => 'statistics',
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
    'title' => 'LLL:EXT:t3sportstats/locallang_db.xml:service_t3sports_playerstats_title', 'description' => 'Statistical data about players', 'subtype' => 'base',
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
    'title' => 'LLL:EXT:t3sportstats/locallang_db.xml:service_t3sports_playertimestats_title', 'description' => 'Statistical data about players', 'subtype' => 'playtime',
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
    'title' => 'LLL:EXT:t3sportstats/locallang_db.xml:service_t3sports_playertimestats_title', 'description' => 'Statistical data about players', 'subtype' => 'goals',
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
    'title' => 'LLL:EXT:t3sportstats/locallang_db.xml:service_t3sports_playerstats_title', 'description' => 'Statistical data about coaches', 'subtype' => 'base',
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
    array(
    'title' => 'LLL:EXT:t3sportstats/locallang_db.xml:service_t3sports_playerstats_title', 'description' => 'Statistical data about referees', 'subtype' => 'base',
    'available' => true, 'priority' => 50, 'quality' => 50,
    'os' => '', 'exec' => '',
    'classFile' => tx_rnbase_util_Extensions::extPath($_EXTKEY).'srv/class.tx_t3sportstats_srv_RefereeStats.php',
    'className' => 'tx_t3sportstats_srv_RefereeStats',
  )
);

tx_rnbase::load('tx_t3sportstats_util_Config');

tx_t3sportstats_util_Config::registerPlayerStatsSimple('goals', '10,11,12,13');
tx_t3sportstats_util_Config::registerPlayerStatsSimple('assists', '31');
tx_t3sportstats_util_Config::registerPlayerStatsSimple('goalshead', '11');
tx_t3sportstats_util_Config::registerPlayerStatsSimple('goalspenalty', '12');
tx_t3sportstats_util_Config::registerPlayerStatsSimple('goalsown', '30');
tx_t3sportstats_util_Config::registerPlayerStatsSimple('cardyellow', '70');
tx_t3sportstats_util_Config::registerPlayerStatsSimple('cardyr', '71');
tx_t3sportstats_util_Config::registerPlayerStatsSimple('cardred', '72');
tx_t3sportstats_util_Config::registerPlayerStatsSimple('changeout', '80');
tx_t3sportstats_util_Config::registerPlayerStatsSimple('changein', '81');
tx_t3sportstats_util_Config::registerPlayerStatsSimple('captain', '200');

// Tore kommen über das Spielergebnis
//tx_t3sportstats_util_Config::registerCoachStatsSimple('goals', '10,11,12,13');
tx_t3sportstats_util_Config::registerCoachStatsSimple('cardyellow', '70');
tx_t3sportstats_util_Config::registerCoachStatsSimple('cardyr', '71');
tx_t3sportstats_util_Config::registerCoachStatsSimple('cardred', '72');
tx_t3sportstats_util_Config::registerCoachStatsSimple('changeout', '80');

tx_t3sportstats_util_Config::registerRefereeStatsSimple('goalspenalty', '12');
tx_t3sportstats_util_Config::registerRefereeStatsSimple('penalty', '12,32');
tx_t3sportstats_util_Config::registerRefereeStatsSimple('cardyellow', '70');
tx_t3sportstats_util_Config::registerRefereeStatsSimple('cardyr', '71');
tx_t3sportstats_util_Config::registerRefereeStatsSimple('cardred', '72');

// Register reports for plugin
tx_t3sportstats_util_Config::registerPlayerStatsReport('default');
tx_t3sportstats_util_Config::registerPlayerStatsReport('scorerlist');
tx_t3sportstats_util_Config::registerPlayerStatsReport('assistlist');

tx_t3sportstats_util_Config::registerCoachStatsReport('default');
tx_t3sportstats_util_Config::registerRefereeStatsReport('default');
