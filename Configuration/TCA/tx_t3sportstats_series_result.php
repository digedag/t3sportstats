<?php

if (!(defined('TYPO3') || defined('TYPO3_MODE'))) {
    exit('Access denied.');
}

$tx_t3sportstats_series_result = [
    'ctrl' => [
        'title' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_result',
        'label' => 'uid',
        'label_alt' => 'club',
        'label_alt_force' => 1,
        'searchFields' => '',
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
        'club' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_result_club',
            'config' => [
                'type' => 'group',
                'allowed' => 'tx_cfcleague_club',
                'readonly' => '1',
                'maxitems' => 1,
                'size' => '1',
            ],
        ],
        'quantity' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_result_quantity',
            'config' => [
                'type' => 'input',
                'size' => '10',
                'readonly' => '1',
            ],
        ],
        'firstmatch' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_result_firstmatch',
            'config' => [
                'type' => 'group',
                'allowed' => 'tx_cfcleague_games',
                'readonly' => '1',
                'maxitems' => 1,
                'size' => '1',
            ],
        ],
        'lastmatch' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_result_lastmatch',
            'config' => [
                'type' => 'group',
                'allowed' => 'tx_cfcleague_games',
                'readonly' => '1',
                'maxitems' => 1,
                'size' => '1',
            ],
        ],
        'parentid' => [
            'type' => 'passthrough'
        ],
        'parenttable' => [
            'type' => 'passthrough'
        ],
        'uniquekey' => [
            'type' => 'passthrough'
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => 'club,quantity,firstmatch,lastmatch',
        ],
    ],
    'palettes' => [
        '1' => [
            'showitem' => '',
        ],
    ],
];

if (\Sys25\RnBase\Utility\TYPO3::isTYPO104OrHigher()) {
    unset($tx_t3sportstats_series_result['interface']['showRecordFieldList']);
}

return $tx_t3sportstats_series_result;
