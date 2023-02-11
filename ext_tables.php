<?php

if (!defined('TYPO3_MODE')) {
    exit('Access denied.');
}

if (TYPO3_MODE == 'BE') {
    // Einbindung einer PageTSConfig
    \Sys25\RnBase\Utility\Extensions::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:t3sportstats/Configuration/PageTS/modWizards.ts">');
    \Sys25\RnBase\Utility\Extensions::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:t3sportstats/Configuration/PageTS/moduleConfig.tss">');

    ////////////////////////////////
    // Submodul anmelden
    ////////////////////////////////
//     $modName = 'web_CfcLeagueM1';
//     \Sys25\RnBase\Utility\Extensions::insertModuleFunction(
//         $modName,
//         System25\T3sports\Backend\Controller\StatisticsController::class,
//         \Sys25\RnBase\Utility\Extensions::extPath($_EXTKEY).'Classes/Controller/Competition.php',
//         'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_module_name'
//     );
}
