<?php

namespace System25\T3sports\Search;

use Sys25\RnBase\Database\Query\Join;
use Sys25\RnBase\Search\SearchBase;
use Sys25\RnBase\Utility\Misc;
use System25\T3sports\Model\Tag;

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
 * Class to search player stats from database.
 *
 * @author Rene Nitzsche
 */
class TagSearch extends SearchBase
{
    protected function getTableMappings()
    {
        $tableMapping = [];
        $tableMapping['TAG'] = $this->getBaseTable();
        $tableMapping['SERIES_SCOPE_MM'] = 'tx_t3sportstats_series_scope_mm';
        $tableMapping['SERIES'] = 'tx_t3sportstats_series';

        // Hook to append other tables
        Misc::callHook('t3sportstats', 'search_Tags_getTableMapping_hook', [
            'tableMapping' => &$tableMapping,
        ], $this);

        return $tableMapping;
    }

    protected function getBaseTableAlias()
    {
        return 'TAG';
    }

    protected function getBaseTable()
    {
        return 'tx_t3sportstats_tags';
    }

    public function getWrapperClass()
    {
        return Tag::class;
    }

    protected function getJoins($tableAliases)
    {
        $join = [];
        if (isset($tableAliases['SERIES']) || isset($tableAliases['SERIES_SCOPE_MM'])) {
            $join[] = new Join('TAG', 'tx_t3sportstats_series_scope_mm', 'TAG.uid = SERIES_SCOPE_MM.uid_foreign AND SERIES_SCOPE_MM.tablenames=\'tx_t3sportstats_tags\'', 'SERIES_SCOPE_MM');
        }

        if (isset($tableAliases['SERIES'])) {
            $join[] = new Join('SERIES_SCOPE_MM', 'tx_t3sportstats_series', 'SERIES.uid = SERIES_SCOPE_MM.uid_local', 'SERIES');
        }

        // Hook to append other tables
        Misc::callHook('t3sportstats', 'search_Tags_getJoins_hook', [
            'join' => &$join,
            'tableAliases' => $tableAliases,
        ], $this);

        return $join;
    }
}
