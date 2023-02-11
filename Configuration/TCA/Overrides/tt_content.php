<?php

defined('TYPO3_MODE') or exit;

call_user_func(function () {
    $extKey = 't3sportstats';

    ////////////////////////////////
    // Plugin Competition anmelden
    ////////////////////////////////

    // Einige Felder ausblenden
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['tx_t3sportstats'] = 'layout,select_key,pages,recursive';

    // Das tt_content-Feld pi_flexform einblenden
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['tx_t3sportstats'] = 'pi_flexform';

    \Sys25\RnBase\Utility\Extensions::addPiFlexFormValue(
        'tx_t3sportstats',
        'FILE:EXT:'.$extKey.'/Configuration/Flexform/plugin_main.xml'
    );

    \Sys25\RnBase\Utility\Extensions::addPlugin(
        [
            'LLL:EXT:'.$extKey.'/Resources/Private/Language/locallang_db.xlf:plugin.t3sportstats.label',
            'tx_t3sportstats',
        ],
        'list_type',
        $extKey
    );
});
