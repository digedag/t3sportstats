<?php

if (!(defined('TYPO3') || defined('TYPO3_MODE'))) {
    exit('Access denied.');
}

call_user_func(function () {
    $extKey = 't3sportstats';

    ////////////////////////////////
    // Plugin Statistik anmelden
    ////////////////////////////////

    $pluginKey = 'tx_t3sportstats';
    // Einige Felder ausblenden
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginKey] = 'layout,select_key,pages,recursive';

    // Das tt_content-Feld pi_flexform einblenden
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginKey] = 'pi_flexform';

    Sys25\RnBase\Utility\Extensions::addPiFlexFormValue(
        $pluginKey,
        'FILE:EXT:'.$extKey.'/Configuration/Flexform/plugin_main.xml'
    );

    Sys25\RnBase\Utility\Extensions::addPlugin(
        [
            'LLL:EXT:'.$extKey.'/Resources/Private/Language/locallang_db.xlf:plugin.t3sportstats.label',
            $pluginKey,
        ],
        'list_type',
        $extKey
    );
    ////////////////////////////////

    // Plugin Serien anmelden
    ////////////////////////////////

    $pluginKey = 'tx_t3sportstats_series';
    // Einige Felder ausblenden
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginKey] = 'layout,select_key,pages,recursive';

    // Das tt_content-Feld pi_flexform einblenden
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginKey] = 'pi_flexform';

    Sys25\RnBase\Utility\Extensions::addPiFlexFormValue(
        $pluginKey,
        'FILE:EXT:'.$extKey.'/Configuration/Flexform/plugin_series.xml'
    );

    Sys25\RnBase\Utility\Extensions::addPlugin(
        [
            'LLL:EXT:'.$extKey.'/Resources/Private/Language/locallang_db.xlf:plugin.t3sportstatsseries.label',
            $pluginKey,
        ],
        'list_type',
        $extKey
    );
});
