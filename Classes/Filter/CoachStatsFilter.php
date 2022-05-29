<?php

namespace System25\T3sports\Filter;

use Sys25\RnBase\Frontend\Filter\BaseFilter;
use Sys25\RnBase\Utility\Strings;
use System25\T3sports\Search\StatsSearchBuilder;
use System25\T3sports\Utility\ScopeController;

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
 * Default filter for coach statistics.
 *
 * @author Rene Nitzsche
 */
class CoachStatsFilter extends BaseFilter
{
    /**
     * Abgeleitete Filter können diese Methode überschreiben und zusätzliche Filter setzen.
     *
     * @param array $fields
     * @param array $options
     * @param \tx_rnbase_IParameters $parameters
     * @param \tx_rnbase_configurations $configurations
     * @param string $confId
     */
    protected function initFilter(&$fields, &$options)
    {
        $parameters = $this->getParameters();
        $configurations = $this->getConfigurations();
        $confId = $this->getConfId();

        // $options['distinct'] = 1;
        // Wir benötigen zuerst die Spalten für WHAT
        $cols = Strings::trimExplode(',', $configurations->get($confId.'columns'));
        $columns = [];
        foreach ($cols as $col) {
            if ($col) {
                $columns[] = 'sum('.$col.') AS '.$col;
            }
        }
        if (count($columns)) {
            $options['what'] .= ','.implode(', ', $columns);
        }
        $scopeArr = ScopeController::handleCurrentScope($parameters, $configurations);
        StatsSearchBuilder::buildCoachStatsByScope($fields, $scopeArr);
    }
}
