<?php

if (!(defined('TYPO3') || defined('TYPO3_MODE'))) {
    exit('Access denied.');
}

$tx_t3sportstats_tags = [
    'ctrl' => [
        'title' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_tags',
        'label' => 'name',
        'searchFields' => 'uid,name,label',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'default_sortby' => 'ORDER BY name',
        'delete' => 'deleted',
        'enablecolumns' => [],
        'iconfile' => 'EXT:cfc_league/Resources/Public/Icons/icon_table.gif',
    ],
    'interface' => [
        'showRecordFieldList' => 'name',
    ],
    'feInterface' => [
        'fe_admin_fieldList' => 'hidden, starttime, fe_group, name',
    ],
    'columns' => [
        'name' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_tags_name',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'max' => '50',
                'eval' => 'required,trim',
            ],
        ],
        'label' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_tags_label',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'max' => '50',
                'eval' => 'required,trim',
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => 'name,label',
        ],
    ],
    'palettes' => [
        '1' => [
            'showitem' => '',
        ],
    ],
];

if (Sys25\RnBase\Utility\TYPO3::isTYPO104OrHigher()) {
    unset($tx_t3sportstats_tags['interface']['showRecordFieldList']);
}

return $tx_t3sportstats_tags;
