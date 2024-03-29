<?php

namespace System25\T3sports\View;

use Sys25\RnBase\Frontend\Marker\ListBuilder;
use Sys25\RnBase\Frontend\Marker\Templates;
use Sys25\RnBase\Frontend\Request\RequestInterface;
use Sys25\RnBase\Frontend\View\ContextInterface;
use Sys25\RnBase\Frontend\View\Marker\BaseView;
use System25\T3sports\Marker\RefereeStatsMarker;
use tx_rnbase;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2023 Rene Nitzsche (rene@system25.de)
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
class RefereeStats extends BaseView
{
    protected function createOutput($template, RequestInterface $request, $formatter)
    {
        $viewData = $request->getViewContext();

        $items = &$viewData->offsetGet('items');
        $listBuilder = tx_rnbase::makeInstance(ListBuilder::class);

        $out = '';
        foreach ($items as $type => $data) {
            $subTemplate = Templates::getSubpart($template, '###'.strtoupper($type).'###');
            $out .= $listBuilder->render($data, $viewData, $subTemplate, RefereeStatsMarker::class, $request
                ->getConfId().$type.'.data.', 'DATA', $formatter);
        }

        return $out;
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
        return '###REFEREESTATS###';
    }
}
