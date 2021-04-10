<?php

if (!defined('TYPO3_MODE')) {
    exit('Access denied.');
}

if (TYPO3_MODE == 'BE') {
    // Einbindung einer PageTSConfig
    tx_rnbase_util_Extensions::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:t3sportstats/Configuration/PageTS/modWizards.ts">');
    tx_rnbase_util_Extensions::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:t3sportstats/Configuration/PageTS/moduleConfig.tss">');

    ////////////////////////////////
    // Submodul anmelden
    ////////////////////////////////
//     $modName = 'web_CfcLeagueM1';
//     tx_rnbase_util_Extensions::insertModuleFunction(
//         $modName,
//         System25\T3sports\Backend\Controller\StatisticsController::class,
//         tx_rnbase_util_Extensions::extPath($_EXTKEY).'Classes/Controller/Competition.php',
//         'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xml:tx_t3sportstats_module_name'
//     );
}
