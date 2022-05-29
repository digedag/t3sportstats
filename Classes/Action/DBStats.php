<?php

namespace System25\T3sports\Action;

use Sys25\RnBase\Database\Connection;
use Sys25\RnBase\Frontend\Controller\AbstractAction;
use Sys25\RnBase\Frontend\Request\RequestInterface;
use Sys25\RnBase\Utility\Strings;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2022 Rene Nitzsche (rene@system25.de)
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

/**
 * Controller.
 */
class DBStats extends AbstractAction
{
    /**
     * @param RequestInterface $request
     *
     * @return string error msg or null
     */
    protected function handleRequest(RequestInterface $request)
    {
        $parameters = $request->getParameters();
        $configurations = $request->getConfigurations();
        $viewData = $request->getViewContext();

        // Zuerst die Art der Statistik ermitteln
        $tables = Strings::trimExplode(',', $configurations->get($this->getConfId().'tables'), 1);
        if (!count($tables)) {
            // Abbruch kein Typ angegeben
            throw new \Exception('No database table configured in: '.$this->getConfId().'tables');
        }

        $statsData = [];
        foreach ($tables as $table) {
            $statsData[$table] = $this->findData($parameters, $configurations, $viewData, $table);
        }
        $viewData->offsetSet('items', $statsData);

        return null;
    }

    private function findData($parameters, $configurations, $viewData, $table)
    {
        // SELECT count(*) FROM table
        $options = [];
        $debug = $configurations->get($this->getConfId().'options.debug');
        if ($debug) {
            $options['debug'] = 1;
        }

        $res = Connection::getInstance()->doSelect('count(*) AS cnt', $table, $options);

        $items['size'] = $res[0]['cnt'];

        return $items;
    }

    protected function getTemplateName()
    {
        return 'dbstats';
    }

    protected function getViewClassName()
    {
        return \System25\T3sports\View\DBStats::class;
    }
}
