<?php

if (!(defined('TYPO3') || defined('TYPO3_MODE'))) {
    exit('Access denied.');
}

$tx_t3sportstats_series = [
    'ctrl' => [
        'title' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series',
        'label' => 'name',
        'label_alt' => 'label',
        'label_alt_force' => 1,
        'searchFields' => 'uid,name,label',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'default_sortby' => 'ORDER BY name',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'iconfile' => 'EXT:cfc_league/Resources/Public/Icons/icon_table.gif',
    ],
    'interface' => [
        'showRecordFieldList' => 'label',
    ],
    'feInterface' => [
        'fe_admin_fieldList' => 'label',
    ],
    'columns' => [
        'name' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_name',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'max' => '50',
                'eval' => 'required,trim',
            ],
        ],
        'label' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_label',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'max' => '50',
                'eval' => 'required,trim',
            ],
        ],
        'saison' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_saison',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_cfcleague_saison',
                'size' => 5,
                'autoSizeMax' => 20,
                'minitems' => 0,
                'maxitems' => 100,
                'MM' => 'tx_t3sportstats_series_scope_mm',
                'MM_match_fields' => [
                    'tablenames' => 'tx_cfcleague_saison',
                ],
            ],
        ],
        'competitiontag' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_tags',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_t3sportstats_tags',
                'size' => 5,
                'autoSizeMax' => 20,
                'minitems' => 0,
                'maxitems' => 100,
                'MM' => 'tx_t3sportstats_series_scope_mm',
                'MM_match_fields' => [
                    'tablenames' => 'tx_t3sportstats_tags',
                ],
            ],
        ],
        'competition' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_competition',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_cfcleague_competition',
                'size' => 5,
                'autoSizeMax' => 20,
                'minitems' => 0,
                'maxitems' => 100,
                'MM' => 'tx_t3sportstats_series_scope_mm',
                'MM_match_fields' => [
                    'tablenames' => 'tx_cfcleague_competition',
                ],
            ],
        ],
        'competitiontype' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:cfc_league_fe/Resources/Private/Language/locallang_db.xlf:plugin.competition.flexform.competitionType',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'items' => [
                    ['LLL:EXT:cfc_league/Resources/Private/Language/locallang_db.xlf:tx_cfcleague_competition.type_league', '1'],
                    ['LLL:EXT:cfc_league/Resources/Private/Language/locallang_db.xlf:tx_cfcleague_competition.type_ko', '2'],
                    ['LLL:EXT:cfc_league/Resources/Private/Language/locallang_db.xlf:tx_cfcleague_competition.type_other', '0'],
                ],
                'size' => 4,
                'minitems' => 0,
                'maxitems' => 4,
                'default' => '',
            ],
        ],
        'matchtype' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_matchtype',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_matchtype_all', 'all'],
                    ['LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_matchtype_home', 'home'],
                    ['LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_matchtype_away', 'away'],
                ],
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 'all',
            ],
        ],
        'obligation' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_obligation',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_obligation_yes', 'yes'],
                    ['LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_obligation_no', 'no'],
                    ['LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_obligation_all', 'all'],
                ],
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 'yes',
            ],
        ],
        'agegroup' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_agegroup',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [['', 0]],
                'foreign_table' => 'tx_cfcleague_group',
                'foreign_table_where' => 'ORDER BY tx_cfcleague_group.sorting',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 0,
            ],
        ],
        'club' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_club',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_cfcleague_club',
                'size' => 5,
                'autoSizeMax' => 20,
                'minitems' => 0,
                'maxitems' => 100,
                'MM' => 'tx_t3sportstats_series_scope_mm',
                'MM_match_fields' => [
                    'tablenames' => 'tx_cfcleague_club',
                ],
            ],
        ],
        'numresults' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_numresults',
            'description' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_numresults_desc',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['1', 1],
                    ['2', 2],
                    ['3', 3],
                    ['4', 4],
                    ['5', 5],
                ],
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'default' => 2,
            ],
        ],

        'rules' => [
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_rules',
            'description' => 'field description',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_t3sportstats_series_rule',
                'foreign_field' => 'parentid',
                'foreign_table_field' => 'parenttable',
                'appearance' => [
                    'showSynchronizationLink' => true,
                    'showAllLocalizationLink' => true,
                    'showPossibleLocalizationRecords' => true,
                ],
            ],
        ],
        'results' => [
            'label' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_results',
            'description' => 'All results for this series',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_t3sportstats_series_result',
                'foreign_field' => 'parentid',
                'foreign_table_field' => 'parenttable',
                //                'foreign_sortby' => 'quantity',
                'appearance' => [
                    'showNewRecordLink' => false,
                ],
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => 'hidden,name,label,saison,competitiontag,competition,competitiontype,matchtype,obligation,agegroup,club,numresults,
            --div--;LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_rules,rules,
            --div--;LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_results,results
            ',
        ],
    ],
    'palettes' => [
        '1' => [
            'showitem' => '',
        ],
    ],
];

if (Sys25\RnBase\Utility\TYPO3::isTYPO104OrHigher()) {
    unset($tx_t3sportstats_series['interface']['showRecordFieldList']);
}

return $tx_t3sportstats_series;
