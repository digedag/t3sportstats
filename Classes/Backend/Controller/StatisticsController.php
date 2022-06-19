<?php

namespace System25\T3sports\Backend\Controller;

use Sys25\RnBase\Frontend\Marker\Templates;
use Sys25\RnBase\Utility\Misc;
use System25\T3sports\Controller\Club\ClubStadiumHandler;
use tx_rnbase;

/***************************************************************
*  Copyright notice
*
*  (c) 2010-2022 Rene Nitzsche <rene@system25.de>
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
 * Module function.
 *
 * @author	Rene Nitzsche <rene@system25.de>
 */
class StatisticsController extends \tx_rnbase_mod_ExtendedModFunc
{
    protected function getContent($template, &$configurations, &$formatter, $formTool)
    {
        $commonStart = Templates::getSubpart($template, '###COMMON_START###');
        $commonEnd = Templates::getSubpart($template, '###COMMON_END###');
        $tabContent = 'Tst';

        $out = $commonStart;
        $out .= $tabContent;
        $out .= $commonEnd;

        return $out;
    }

    protected function getFuncId()
    {
        return 'funct3sportstats';
    }

    /**
     * Liefert die Einträge für das Tab-Menü.
     * return array.
     */
    protected function getSubMenuItems()
    {
        $menuItems = [];
        $menuItems[] = tx_rnbase::makeInstance(ClubStadiumHandler::class);
        Misc::callHook(
            'cfc_league',
            'modClub_tabItems',
            ['tabItems' => &$menuItems],
            $this
        );

        return $menuItems;
    }

    protected function makeSubSelectors(&$selStr)
    {
        return false;
    }
}
