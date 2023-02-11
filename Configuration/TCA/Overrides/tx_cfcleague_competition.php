<?php

if (!defined('TYPO3_MODE')) {
    exit('Access denied.');
}

$columns = [
    'tags' => [
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
            'MM' => 'tx_t3sportstats_tags_mm',
            'MM_match_fields' => [
                'tablenames' => 'tx_cfcleague_competition',
            ],
        ],
    ],
];

$table = [
    'columns' => &$columns,
];
\Sys25\RnBase\Backend\Utility\TcaTool::configureWizards($table, [
    'tags' => [
        'suggest' => true,
        'targettable' => 'tx_t3sportstats_tags',
    ],
]);

\Sys25\RnBase\Utility\Extensions::addTCAcolumns('tx_cfcleague_competition', $columns, 1);
\Sys25\RnBase\Utility\Extensions::addToAllTCAtypes('tx_cfcleague_competition', 'tags', '', 'after:point_system');
