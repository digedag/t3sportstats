<?php

namespace System25\T3sports\View;

use Sys25\RnBase\Frontend\View\Marker\BaseView;
use Sys25\RnBase\Frontend\Request\RequestInterface;
use Sys25\RnBase\Frontend\View\ContextInterface;
use System25\T3sports\Marker\PlayerStatsMarker;

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
 * Viewklasse für die Darstellung von Nutzerinformationen aus der DB.
 */
class PlayerStats extends BaseView
{
    private $playerIds = [];

    protected function createOutput($template, RequestInterface $request, $formatter)
    {
        $configurations = $request->getConfigurations();
        $viewData = $request->getViewContext();
        $items = &$viewData->offsetGet('items');
        $listBuilder = \tx_rnbase::makeInstance('tx_rnbase_util_ListBuilder');
        $team = $viewData->offsetGet('team');
        if ($team) {
            $this->playerIds = array_flip(\Tx_Rnbase_Utility_Strings::intExplode(',', $team->getProperty('players')));
            $listBuilder->addVisitor([
                $this,
                'highlightPlayer',
            ]);
        }

        $out = '';
        foreach ($items as $type => $data) {
            // Marker class can be configured
            $markerClass = $configurations->get($request->getConfId().$type.'.markerClass');
            if (!$markerClass) {
                $markerClass = PlayerStatsMarker::class;
            }

            $subTemplate = \tx_rnbase_util_Templates::getSubpart($template, '###'.strtoupper($type).'###');
            $out .= $listBuilder->render($data, $viewData, $subTemplate, $markerClass, $request
                ->getConfId().$type.'.data.', 'DATA', $formatter);
        }

        return $out;
    }

    public function highlightPlayer($item)
    {
        if (array_key_exists($item->getProperty('player'), $this->playerIds)) {
            $item->setProperty('hlTeam', 1);
        }
    }

    /**
     * Subpart der im HTML-Template geladen werden soll.
     * Dieser wird der Methode
     * createOutput automatisch als $template übergeben.
     *
     * @return string
     */
    protected function getMainSubpart(ContextInterface $viewData)
    {
        return '###PLAYERSTATS###';
    }
}
