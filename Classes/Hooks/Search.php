<?php

namespace System25\T3sports\Hooks;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2020 Rene Nitzsche
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
    public function getTableMappingMatch($params, $parent)
    {
        $params['tableMapping']['PLAYERSTAT'] = 'tx_t3sportstats_players';
        $params['tableMapping']['COACHSTAT'] = 'tx_t3sportstats_coachs';
        $params['tableMapping']['REFEREESTAT'] = 'tx_t3sportstats_referees';
    }

    public function getJoinsMatch($params, $parent)
    {
        if (isset($params['tableAliases']['PLAYERSTAT'])) {
            $params['join'] .= ' JOIN tx_t3sportstats_players ON tx_cfcleague_games.uid = tx_t3sportstats_players.t3match ';
        }
        if (isset($params['tableAliases']['COACHSTAT'])) {
            $params['join'] .= ' JOIN tx_t3sportstats_coachs ON tx_cfcleague_games.uid = tx_t3sportstats_coachs.t3match ';
        }
        if (isset($params['tableAliases']['REFEREESTAT'])) {
            $params['join'] .= ' JOIN tx_t3sportstats_referees ON tx_cfcleague_games.uid = tx_t3sportstats_referees.t3match ';
        }
    }
}
