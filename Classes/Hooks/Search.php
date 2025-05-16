<?php

namespace System25\T3sports\Hooks;

use Sys25\RnBase\Database\Query\Join;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2021 Rene Nitzsche
 *  Contact: rene@system25.de
 *  All rights reserved
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 ***************************************************************/

/**
 * Make additional join for match search.
 *
 * @author Rene Nitzsche
 */
class Search
{
    public function getTableMappingMatch(&$params, $parent)
    {
        $params['tableMapping']['PLAYERSTAT'] = 'tx_t3sportstats_players';
        $params['tableMapping']['COACHSTAT'] = 'tx_t3sportstats_coachs';
        $params['tableMapping']['REFEREESTAT'] = 'tx_t3sportstats_referees';
        $params['tableMapping']['TAG'] = 'tx_t3sportstats_tags';
        $params['tableMapping']['TAGMM'] = 'tx_t3sportstats_tags_mm';
        $params['tableMapping']['SERIESRESULT'] = 'tx_t3sportstats_series_result';
        $params['tableMapping']['SERIESRESULTMM'] = 'tx_t3sportstats_series_result_mm';
        $params['tableMapping']['TAGMM'] = 'tx_t3sportstats_tags_mm';
    }

    public function getJoinsMatch(&$params, $parent)
    {
        if (isset($params['tableAliases']['PLAYERSTAT'])) {
            $params['join'][] = new Join('MATCH', 'tx_t3sportstats_players', 'MATCH.uid = PLAYERSTAT.t3match', 'PLAYERSTAT');
        }
        if (isset($params['tableAliases']['COACHSTAT'])) {
            $params['join'][] = new Join('MATCH', 'tx_t3sportstats_coachs', 'MATCH.uid = COACHSTAT.t3match', 'COACHSTAT');
        }
        if (isset($params['tableAliases']['REFEREESTAT'])) {
            $params['join'][] = new Join('MATCH', 'tx_t3sportstats_referees', 'MATCH.uid = REFEREESTAT.t3match', 'REFEREESTAT');
        }
        if (isset($params['tableAliases']['TAG']) || isset($params['tableAliases']['TAGMM'])) {
            if (!isset($params['tableAliases']['COMPETITION'])) {
                $params['join'][] = new Join('MATCH', 'tx_cfcleague_competition', 'MATCH.competition = COMPETITION.uid', 'COMPETITION');
            }
            $params['join'][] = new Join('COMPETITION', 'tx_t3sportstats_tags_mm', 'COMPETITION.UID = TAGMM.uid_local AND TAGMM.tablenames = \'tx_cfcleague_competition\' ', 'TAGMM');
        }
        if (isset($params['tableAliases']['TAG'])) {
            $params['join'][] = new Join('TAGMM', 'tx_t3sportstats_tags', 'TAGMM.uid_foreign = TAG.uid', 'TAG');
        }

        if (isset($params['tableAliases']['SERIESRESULT']) || isset($params['tableAliases']['SERIESRESULTMM'])) {
            $params['join'][] = new Join('MATCH', 'tx_t3sportstats_series_result_mm', 'MATCH.uid = SERIESRESULTMM.uid_foreign', 'SERIESRESULTMM');
            $params['join'][] = new Join('SERIESRESULTMM', 'tx_t3sportstats_series_result', 'SERIESRESULT.UID = SERIESRESULTMM.uid_local AND SERIESRESULTMM.tablenames = \'tx_cfcleague_games\' ', 'SERIESRESULT');
        }
    }
}
