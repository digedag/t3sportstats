<?php

namespace System25\T3sports\Action;

use Sys25\RnBase\Configuration\ConfigurationInterface;
use Sys25\RnBase\Frontend\Controller\AbstractAction;
use Sys25\RnBase\Frontend\Filter\BaseFilter;
use Sys25\RnBase\Frontend\Request\RequestInterface;
use Sys25\RnBase\Utility\Strings;
use System25\T3sports\Model\Team;
use System25\T3sports\Service\StatsServiceRegistry;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2020 Rene Nitzsche (rene@system25.de)
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
class CoachStats extends AbstractAction
{
    /**
     * @param RequestInterface $request
     *
     * @return string error msg or null
     */
    protected function handleRequest(RequestInterface $request)
    {
        $configurations = $request->getConfigurations();
        $viewData = $request->getViewContext();

        // Zuerst die Art der Statistik ermitteln
        $types = Strings::trimExplode(',', $configurations->get($this->getConfId().'statisticTypes'), 1);
        if (!count($types)) {
            // Abbruch kein Typ angegeben
            throw new \Exception('No statistics type configured in: '.$this->getConfId().'statisticTypes');
        }

        $statsData = [];
        foreach ($types as $type) {
            $statsData[$type] = $this->findData($request, $viewData, $type);
        }
        $viewData->offsetSet('items', $statsData);
        $teamId = $configurations->get($this->getConfId().'highlightTeam');
        if ($teamId) {
            $team = Team::getInstance($teamId);
            if (is_object($team) && $team->isValid()) {
                $viewData->offsetSet('team', $team);
            }
        }

        return null;
    }

    private function findData(RequestInterface $request, $viewData, $type)
    {
        $srv = (new StatsServiceRegistry())->getStatisticService();
        $confId = $this->getConfId().$type.'.';
        $filter = BaseFilter::createFilter($request, $confId);

        $fields = [];
        $options = [
            'enablefieldsoff' => 1,
        ];
        $filter->init($fields, $options);
        $debug = $request->getConfigurations()->get($this->getConfId().'options.debug');
        if ($debug) {
            $options['debug'] = 1;
        }

        self::handlePageBrowser($request->getConfigurations(), $confId.'data.pagebrowser', $viewData, $fields, $options, [
            'searchcallback' => [
                $srv,
                'searchCoachStats',
            ],
            'pbid' => $type.'ps',
        ]);

        $items = $srv->searchCoachStats($fields, $options);

        return $items;
    }

    /**
     * Pagebrowser vorbereiten.
     * Für die Statistik benötigen wir eine spezielle Anfrage zu Ermittlung der Listenlänge.
     *
     * @param string $confid
     *            Die Confid des PageBrowsers. z.B. myview.org.pagebrowser ohne Punkt!
     * @param \tx_rnbase_configurations $configurations
     * @param \ArrayObject $viewdata
     * @param array $fields
     * @param array $options
     */
    private static function handlePageBrowser(ConfigurationInterface $configurations, $confid, &$viewdata, &$fields, &$options, $cfg = [])
    {
        $confid .= '.';
        if (is_array($configurations->get($confid))) {
            // Die Gesamtzahl der Items ist entweder im Limit gesetzt oder muss ermittelt werden
            $listSize = intval($options['limit']);
            if (!$listSize) {
                // Mit Pagebrowser benötigen wir zwei Zugriffe, um die Gesamtanzahl der Items zu ermitteln
                $options['count'] = 1;
                $oldWhat = $options['what'];
                $options['what'] = 'count(DISTINCT coach) AS cnt';
                $searchCallback = $cfg['searchcallback'];
                if (!$searchCallback) {
                    throw new \Exception('No search callback defined!');
                }
                $listSize = call_user_func($searchCallback, $fields, $options);
                // $listSize = $service->search($fields, $options);
                unset($options['count']);
                $options['what'] = $oldWhat;
            }
            // PageBrowser initialisieren
            $pbId = $cfg['pbid'] ? $cfg['pbid'] : 'pb';
            $pageBrowser = \tx_rnbase::makeInstance('tx_rnbase_util_PageBrowser', $pbId);
            $pageSize = $configurations->getInt($confid.'limit');

            $pageBrowser->setState($configurations->getParameters(), $listSize, $pageSize);
            $limit = $pageBrowser->getState();
            $options = array_merge($options, $limit);
            if ($viewdata) {
                $viewdata->offsetSet('pagebrowser', $pageBrowser);
            }
        }
    }

    public function getTemplateName()
    {
        return 'coachstats';
    }

    public function getViewClassName()
    {
        return \System25\T3sports\View\CoachStats::class;
    }
}
