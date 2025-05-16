<?php

if (!(defined('TYPO3') || defined('TYPO3_MODE'))) {
    exit('Access denied.');
}

if (Sys25\RnBase\Utility\Environment::isBackend()) {
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
