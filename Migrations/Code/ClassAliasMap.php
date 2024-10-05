<?php

declare(strict_types=1);

return [
    'tx_t3sportstats_actions_SeriesList' => System25\T3sports\Frontend\Action\SeriesList::class,
    'tx_t3sportstats_actions_PlayerStats' => System25\T3sports\Frontend\Action\PlayerStats::class,
    'tx_t3sportstats_actions_CoachStats' => System25\T3sports\Frontend\Action\CoachStats::class,
    'tx_t3sportstats_actions_RefereeStats' => System25\T3sports\Frontend\Action\RefereeStats::class,
    'tx_t3sportstats_actions_DBStats' => System25\T3sports\Frontend\Action\DBStats::class,
    'tx_t3sportstats_util_Config' => System25\T3sports\Utility\StatsConfig::class,
];
