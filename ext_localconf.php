<?php

if (!(defined('TYPO3') || defined('TYPO3_MODE'))) {
    exit('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league']['clearStatistics_hook'][] = 'System25\T3sports\Hooks\ClearStats->clearStats4Comp';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league']['mergeProfiles_hook'][] = 'System25\T3sports\Hooks\MergeProfiles->mergeProfile';

// Hook for match search
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league']['search_Match_getTableMapping_hook'][] = 'System25\T3sports\Hooks\Search->getTableMappingMatch';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league']['search_Match_getJoins_hook'][] = 'System25\T3sports\Hooks\Search->getJoinsMatch';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league_fe']['search_Match_getTableMapping_hook'][] = 'System25\T3sports\Hooks\Search->getTableMappingMatch';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league_fe']['search_Match_getJoins_hook'][] = 'System25\T3sports\Hooks\Search->getJoinsMatch';

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league']['search_Club_getTableMapping_hook'][] = 'System25\T3sports\Hooks\ClubSearch->getTableMappingMatch';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league']['search_Club_getJoins_hook'][] = 'System25\T3sports\Hooks\ClubSearch->getJoinsMatch';

// Hook for profile marker
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league_fe']['profileMarker_afterSubst'][] = 'System25\T3sports\Hooks\Marker->parseProfile';

// Hook for match filter
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league_fe']['filterMatch_setfields'][] = 'System25\T3sports\Hooks\Filter->handleMatchFilter';

if (!\Sys25\RnBase\Utility\TYPO3::isTYPO104OrHigher()) {
    $provider = \System25\T3sports\Service\StatsIndexerProvider::getInstance();
    $provider->addStatsIndexer(new \System25\T3sports\StatsIndexer\CoachStats());
    $provider->addStatsIndexer(new \System25\T3sports\StatsIndexer\PlayerStats());
    $provider->addStatsIndexer(new \System25\T3sports\StatsIndexer\PlayerGoalStats());
    $provider->addStatsIndexer(new \System25\T3sports\StatsIndexer\PlayerTimeStats());
    $provider->addStatsIndexer(new \System25\T3sports\StatsIndexer\RefereeStats());

    $provider = \System25\T3sports\Series\SeriesRuleProvider::getInstance();
    $provider->addSeriesRule(new \System25\T3sports\Series\Rule\WinRule());
    $provider->addSeriesRule(new \System25\T3sports\Series\Rule\LostRule());
}

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

if (\Sys25\RnBase\Utility\Environment::isBackend()) {
    // Einbindung einer PageTSConfig
    // since T3 12 pagets is loaded by convention
    if (!\Sys25\RnBase\Utility\TYPO3::isTYPO121OrHigher()) {
        \Sys25\RnBase\Utility\Extensions::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:t3sportstats/Configuration/PageTS/modWizards.tsconfig">');
        \Sys25\RnBase\Utility\Extensions::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:t3sportstats/Configuration/PageTS/moduleConfig.tsconfig">');
    }
}
