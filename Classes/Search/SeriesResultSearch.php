<?php

namespace System25\T3sports\Search;

use Sys25\RnBase\Database\Query\Join;
use Sys25\RnBase\Search\SearchBase;
use Sys25\RnBase\Utility\Misc;
use System25\T3sports\Model\SeriesResult;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2024 Rene Nitzsche
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
 * Class to search series results from database.
 *
 * @author Rene Nitzsche
 */
class SeriesResultSearch extends SearchBase
{
    protected function getTableMappings()
    {
        $tableMapping = [];
        $tableMapping['SERIESRESULT'] = $this->getBaseTable();
        $tableMapping['SERIES'] = 'tx_t3sportstats_series';

        // Hook to append other tables
        Misc::callHook('t3sportstats', 'search_SeriesResult_getTableMapping_hook', [
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
        return 'SERIESRESULT';
    }

    protected function getBaseTable()
    {
        return 'tx_t3sportstats_series_result';
    }

    public function getWrapperClass()
    {
        return SeriesResult::class;
    }

    protected function getJoins($tableAliases)
    {
        $join = [];
        if (isset($tableAliases['SERIES'])) {
            $join[] = new Join('SERIESRESULT', 'tx_t3sportstats_series', 'SERIES.uid = SERIESRESULT.parentid', 'SERIES');
        }

        // Hook to append other tables
        Misc::callHook('t3sportstats', 'search_SeriesResult_getJoins_hook', [
            'join' => &$join,
            'tableAliases' => $tableAliases,
        ], $this);

        return $join;
    }
}
