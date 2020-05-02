<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'System25\T3sports\Hooks\AfterDBHook';


$GLOBALS ['TYPO3_CONF_VARS']['EXTCONF']['cfc_league']['clearStatistics_hook'][] = 'System25\T3sports\Hooks\ClearStats->clearStats4Comp';
$GLOBALS ['TYPO3_CONF_VARS']['EXTCONF']['cfc_league']['mergeProfiles_hook'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_t3sportstats_hooks_MergeProfiles.php:&tx_t3sportstats_hooks_MergeProfiles->mergeProfile';

// Hook for match search
$GLOBALS ['TYPO3_CONF_VARS']['EXTCONF']['cfc_league_fe']['search_Match_getTableMapping_hook'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_t3sportstats_hooks_Search.php:&tx_t3sportstats_hooks_Search->getTableMappingMatch';
$GLOBALS ['TYPO3_CONF_VARS']['EXTCONF']['cfc_league_fe']['search_Match_getJoins_hook'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_t3sportstats_hooks_Search.php:&tx_t3sportstats_hooks_Search->getJoinsMatch';

// Hook for profile marker
$GLOBALS ['TYPO3_CONF_VARS']['EXTCONF']['cfc_league_fe']['profileMarker_afterSubst'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_t3sportstats_hooks_Marker.php:&tx_t3sportstats_hooks_Marker->parseProfile';

// Hook for match filter
$GLOBALS ['TYPO3_CONF_VARS']['EXTCONF']['cfc_league_fe']['filterMatch_setfields'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_t3sportstats_hooks_Filter.php:&tx_t3sportstats_hooks_Filter->handleMatchFilter';

require(tx_rnbase_util_Extensions::extPath('t3sportstats').'srv/ext_localconf.php');


// Register a new matchnote type
tx_cfcleague_util_Misc::registerMatchNote('LLL:EXT:t3sportstats/locallang_db.xml:tx_cfcleague_match_notes.type.goalfreekick', '13');
