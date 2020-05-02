<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2017 Rene Nitzsche
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
tx_rnbase::load('tx_rnbase_filter_BaseFilter');
tx_rnbase::load('tx_t3sportstats_search_Builder');
tx_rnbase::load('tx_cfcleaguefe_util_ScopeController');
tx_rnbase::load('Tx_Rnbase_Utility_Strings');

/**
 * Default filter for player statistics.
 *
 * @author Rene Nitzsche
 */
class tx_t3sportstats_filter_PlayerStats extends tx_rnbase_filter_BaseFilter
{
    /**
     * Abgeleitete Filter können diese Methode überschreiben und zusätzliche Filter setzen.
     *
     * @param array $fields
     * @param array $options
     * @param tx_rnbase_IParameters $parameters
     * @param tx_rnbase_configurations $configurations
     * @param string $confId
     */
    protected function initFilter(&$fields, &$options, &$parameters, &$configurations, $confId)
    {
        // Wir benötigen zuerst die Spalten für WHAT
        $cols = Tx_Rnbase_Utility_Strings::trimExplode(',', $configurations->get($confId.'columns'));
        $columns = [];
        foreach ($cols as $col) {
            if ($col) {
                $columns[] = 'sum('.$col.') AS '.$col;
            }
        }
        if (count($columns)) {
            $options['what'] .= ', '.implode(', ', $columns);
        }
        $scopeArr = tx_cfcleaguefe_util_ScopeController::handleCurrentScope($parameters, $configurations);
        tx_t3sportstats_search_Builder::buildPlayerStatsByScope($fields, $scopeArr);
    }
}
