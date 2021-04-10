<?php

namespace System25\T3sports\Search;

use Sys25\RnBase\Database\Query\Join;
use Sys25\RnBase\Search\SearchBase;
use System25\T3sports\Model\CoachStat;

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
 * Class to search player stats from database.
 *
 * @author Rene Nitzsche
 */
class SearchCoachStats extends SearchBase
{
    protected function getTableMappings()
    {
        $tableMapping = [];
        $tableMapping['COACHSTAT'] = 'tx_t3sportstats_coachs';
        $tableMapping['COACH'] = 'tx_cfcleague_profiles';
        $tableMapping['MATCH'] = 'tx_cfcleague_games';
        $tableMapping['COMPETITION'] = 'tx_cfcleague_competition';
        $tableMapping['CLUB'] = 'tx_cfcleague_club';
        $tableMapping['CLUBOPP'] = 'tx_cfcleague_club';
        // Hook to append other tables
        \tx_rnbase_util_Misc::callHook('t3sportstats', 'search_CoachStats_getTableMapping_hook', [
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
        return 'COACHSTAT';
    }

    protected function getBaseTable()
    {
        return 'tx_t3sportstats_coachs';
    }

    public function getWrapperClass()
    {
        return CoachStat::class;
    }

    protected function getJoins($tableAliases)
    {
        $join = [];
        if (isset($tableAliases['MATCH'])) {
            $join[] = new Join('COACHSTAT', 'tx_cfcleague_games', 'MATCH.uid = COACHSTAT.t3match', 'MATCH');
        }
        if (isset($tableAliases['COACH'])) {
            $join[] = new Join('COACHSTAT', 'tx_cfcleague_profiles', 'COACH.uid = COACHSTAT.coach', 'COACH');
        }
        if (isset($tableAliases['COMPETITION'])) {
            $join[] = new Join('COACHSTAT', 'tx_cfcleague_competition', 'COMPETITION.uid = COACHSTAT.competition', 'COMPETITION');
        }
        if (isset($tableAliases['CLUB'])) {
            $join[] = new Join('COACHSTAT', 'tx_cfcleague_club', 'CLUB.uid = COACHSTAT.club', 'CLUB');
        }
        if (isset($tableAliases['CLUBOPP'])) {
            $join[] = new Join('COACHSTAT', 'tx_cfcleague_club', 'CLUBOPP.uid = COACHSTAT.clubopp', 'CLUBOPP');
        }

        // Hook to append other tables
        \tx_rnbase_util_Misc::callHook('t3sportstats', 'search_CoachStats_getJoins_hook', [
            'join' => &$join,
            'tableAliases' => $tableAliases,
        ], $this);

        return $join;
    }
}
