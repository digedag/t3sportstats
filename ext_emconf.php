<?php

//#######################################################################
// Extension Manager/Repository config file for ext: "t3sportstats"
//
// Auto generated 12-02-2008 16:47
//
// Manual updates:
// Only the data in the array - anything else is removed by next write.
// "version" and "dependencies" must not be touched!
//#######################################################################

$EM_CONF[$_EXTKEY] = [
    'title' => 'Statistics for T3sports',
    'description' => 'Statistics extension for T3sports.',
    'category' => 'plugin',
    'author' => 'Rene Nitzsche',
    'author_email' => 'rene@system25.de',
    'author_company' => 'System 25',
    'version' => '1.2.1',
    'dependencies' => '',
    'module' => '',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-10.4.99',
            'rn_base' => '1.13.0-0.0.0',
            'cfc_league' => '1.7.0-0.0.0',
            'cfc_league_fe' => '1.7.0-0.0.0',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
