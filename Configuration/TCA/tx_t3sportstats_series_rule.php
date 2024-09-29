<?php

if (!(defined('TYPO3') || defined('TYPO3_MODE'))) {
    exit('Access denied.');
}

$tx_t3sportstats_series_rule = [
    'ctrl' => [
        'title' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_rule',
        'label' => 'rulealias',
        'searchFields' => 'rulealias',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'hideTable' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
        ],
        'iconfile' => 'EXT:cfc_league/Resources/Public/Icons/icon_table.gif',
    ],
    'interface' => [
        'showRecordFieldList' => 'rulealias',
    ],
    'feInterface' => [
        'fe_admin_fieldList' => 'rulealias',
    ],
    'columns' => [
        'rulealias' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_rule_alias',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'itemsProcFunc' => System25\T3sports\Series\SeriesRuleProvider::class.'->getRules4Tca',
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],
        'config' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_rule_config',
            'config' => [
                'type' => 'text',
                'cols' => '30',
                'rows' => '5',
            ],
        ],

    ],
    'types' => [
        '0' => [
            'showitem' => 'rulealias,config',
        ],
    ],
    'palettes' => [
        '1' => [
            'showitem' => '',
        ],
    ],
];

if (\Sys25\RnBase\Utility\TYPO3::isTYPO104OrHigher()) {
    unset($tx_t3sportstats_series_rule['interface']['showRecordFieldList']);
}

return $tx_t3sportstats_series_rule;
