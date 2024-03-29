<?php

namespace System25\T3sports\Search;

use Sys25\RnBase\Database\Query\Join;
use Sys25\RnBase\Search\SearchBase;
use Sys25\RnBase\Utility\Misc;
use System25\T3sports\Model\PlayerStat;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2022 Rene Nitzsche
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
 * Class to search player stats from database.
 *
 * @author Rene Nitzsche
 */
class SearchPlayerStats extends SearchBase
{
    protected function getTableMappings()
    {
        $tableMapping = [];
        $tableMapping['PLAYERSTAT'] = 'tx_t3sportstats_players';
        $tableMapping['PLAYER'] = 'tx_cfcleague_profiles';
        $tableMapping['MATCH'] = 'tx_cfcleague_games';
        $tableMapping['COMPETITION'] = 'tx_cfcleague_competition';
        $tableMapping['CLUB'] = 'tx_cfcleague_club';
        $tableMapping['CLUBOPP'] = 'tx_cfcleague_club';
        // Hook to append other tables
        Misc::callHook('t3sportstats', 'search_PlayerStats_getTableMapping_hook', [
            'tableMapping' => &$tableMapping,
        ], $this);

        return $tableMapping;
    }

    protected function useAlias()
    {
        return true;
    }

    protected function getBaseTableAlias()
    {
        return 'PLAYERSTAT';
    }

    protected function getBaseTable()
    {
        return 'tx_t3sportstats_players';
    }

    public function getWrapperClass()
    {
        return PlayerStat::class;
    }

    protected function getJoins($tableAliases)
    {
        $join = [];

        if (isset($tableAliases['MATCH'])) {
            $join[] = new Join('PLAYERSTAT', 'tx_cfcleague_games', 'MATCH.uid = PLAYERSTAT.t3match', 'MATCH');
        }
        if (isset($tableAliases['PLAYER'])) {
            $join[] = new Join('PLAYERSTAT', 'tx_cfcleague_profiles', 'PLAYER.uid = PLAYERSTAT.player', 'PLAYER');
        }
        if (isset($tableAliases['COMPETITION'])) {
            $join[] = new Join('PLAYERSTAT', 'tx_cfcleague_competition', 'COMPETITION.uid = PLAYERSTAT.competition', 'COMPETITION');
        }
        if (isset($tableAliases['CLUB'])) {
            $join[] = new Join('PLAYERSTAT', 'tx_cfcleague_club', 'CLUB.uid = PLAYERSTAT.club', 'CLUB');
        }
        if (isset($tableAliases['CLUBOPP'])) {
            $join[] = new Join('PLAYERSTAT', 'tx_cfcleague_club', 'CLUBOPP.uid = PLAYERSTAT.clubopp', 'CLUBOPP');
        }

        // Hook to append other tables
        Misc::callHook('t3sportstats', 'search_PlayerStats_getJoins_hook', [
            'join' => &$join,
            'tableAliases' => $tableAliases,
        ], $this);

        return $join;
    }
}
