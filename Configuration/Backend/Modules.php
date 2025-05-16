<?php

return [
    'web_CfcLeagueM1_statistics' => [
        'parent' => 'web_CfcLeagueM1',
        'access' => 'user',
        'workspaces' => '*',
        'iconIdentifier' => 'ext-cfcleague-ext-default',
        'path' => '/module/web/t3sports/statistics',
        'labels' => [
            'title' => 'LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_module_name',
        ],
        'routes' => [
            '_default' => [
                'target' => System25\T3sports\Backend\Controller\StatisticsController::class.'::main',
            ],
        ],
        'moduleData' => [
            'langFiles' => [],
            'pages' => '0',
            'depth' => 0,
        ],
    ],
];
