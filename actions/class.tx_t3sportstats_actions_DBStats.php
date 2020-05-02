<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2017 Rene Nitzsche (rene@system25.de)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
tx_rnbase::load('tx_rnbase_action_BaseIOC');
tx_rnbase::load('Tx_Rnbase_Utility_Strings');
tx_rnbase::load('Tx_Rnbase_Database_Connection');

/**
 * Controller.
 */
class tx_t3sportstats_actions_DBStats extends tx_rnbase_action_BaseIOC
{
    /**
     * @param array_object $parameters
     * @param tx_rnbase_configurations $configurations
     * @param array $viewData
     *
     * @return string error msg or null
     */
    public function handleRequest(&$parameters, &$configurations, &$viewData)
    {
        // Zuerst die Art der Statistik ermitteln
        $tables = Tx_Rnbase_Utility_Strings::trimExplode(',', $configurations->get($this->getConfId().'tables'), 1);
        if (!count($tables)) {
            // Abbruch kein Typ angegeben
            throw new Exception('No database table configured in: '.$this->getConfId().'tables');
        }

        $statsData = array();
        foreach ($tables as $table) {
            $statsData[$table] = $this->findData($parameters, $configurations, $viewData, $table);
        }
        $viewData->offsetSet('items', $statsData);

        return null;
    }

    private function findData($parameters, $configurations, $viewData, $table)
    {
        // SELECT count(*) FROM table
        $options = array();
        $debug = $configurations->get($this->getConfId().'options.debug');
        if ($debug) {
            $options['debug'] = 1;
        }

        $res = Tx_Rnbase_Database_Connection::getInstance()->doSelect('count(*) AS cnt', $table, $options);

        $items['size'] = $res[0]['cnt'];

        return $items;
    }

    public function getTemplateName()
    {
        return 'dbstats';
    }

    public function getViewClassName()
    {
        return 'tx_t3sportstats_views_DBStats';
    }
}
