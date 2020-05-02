<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Rene Nitzsche <rene@system25.de>
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

tx_rnbase::load('tx_rnbase_mod_ExtendedModFunc');

tx_rnbase::load('tx_rnbase_util_Templates');
tx_rnbase::load('tx_rnbase_util_BaseMarker');
tx_rnbase::load('tx_rnbase_util_TYPO3');

/**
 * Module function.
 *
 * @author	Rene Nitzsche <rene@system25.de>
 */
class tx_t3sportstats_mod_index extends tx_rnbase_mod_ExtendedModFunc
{
    protected function getContent($template, &$configurations, &$formatter, $formTool)
    {
        $commonStart = tx_rnbase_util_Templates::getSubpart($template, '###COMMON_START###');
        $commonEnd = tx_rnbase_util_Templates::getSubpart($template, '###COMMON_END###');
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
        $menuItems = array();
        $menuItems[] = tx_rnbase::makeInstance('tx_cfcleague_mod1_handler_ClubStadiums');
        tx_rnbase_util_Misc::callHook(
            'cfc_league',
            'modClub_tabItems',
            array('tabItems' => &$menuItems),
            $this
        );

        return $menuItems;
    }

    protected function makeSubSelectors(&$selStr)
    {
        return false;
    }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3sportstats/mod1/class.tx_t3sportstats_mod_index.php']) {
    include_once $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3sportstats/mod1/class.tx_t3sportstats_mod_index.php'];
}
