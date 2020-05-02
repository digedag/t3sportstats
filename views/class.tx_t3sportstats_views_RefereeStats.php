<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2018 Rene Nitzsche (rene@system25.de)
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
tx_rnbase::load('tx_rnbase_view_Base');
tx_rnbase::load('tx_rnbase_util_Templates');

/**
 * Viewklasse für die Darstellung von Nutzerinformationen aus der DB.
 */
class tx_t3sportstats_views_RefereeStats extends tx_rnbase_view_Base
{
    public function createOutput($template, &$viewData, &$configurations, &$formatter)
    {
        $items = &$viewData->offsetGet('items');
        $listBuilder = tx_rnbase::makeInstance('tx_rnbase_util_ListBuilder');

        $out = '';
        foreach ($items as $type => $data) {
            $subTemplate = tx_rnbase_util_Templates::getSubpart($template, '###'.strtoupper($type).'###');
            $out .= $listBuilder->render($data, $viewData, $subTemplate, 'tx_t3sportstats_marker_RefereeStats', $this->getController()
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
    public function getMainSubpart(&$viewData)
    {
        return '###REFEREESTATS###';
    }
}
