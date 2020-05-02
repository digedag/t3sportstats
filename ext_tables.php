<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

tx_rnbase_util_Extensions::addStaticFile($_EXTKEY, 'static/ts/', 'T3sportstats');

if (TYPO3_MODE == 'BE') {
    // Einbindung einer PageTSConfig
    tx_rnbase_util_Extensions::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:t3sportstats/Configuration/PageTS/modWizards.ts">');
    tx_rnbase_util_Extensions::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:t3sportstats/mod/pageTSconfig.txt">');

    ////////////////////////////////
    // Submodul anmelden
    ////////////////////////////////
// 	tx_rnbase_util_Extensions::insertModuleFunction(
// 		'web_txcfcleagueM1',
// 		'tx_t3sportstats_mod_index',
// 		tx_rnbase_util_Extensions::extPath($_EXTKEY).'mod/class.tx_t3sportstats_mod_index.php',
// 		'LLL:EXT:t3sportstats/mod/locallang.xml:tx_t3sportstats_module_name'
// 	);
}
