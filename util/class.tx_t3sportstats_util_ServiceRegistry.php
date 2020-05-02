<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010-2016 Rene Nitzsche (rene@system25.de)
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

tx_rnbase::load('tx_rnbase_util_Misc');

/**
 * Zentrale Klasse für den Zugriff auf verschiedene Services.
 */
class tx_t3sportstats_util_ServiceRegistry
{
    /**
     * Liefert den Statistik-Service.
     *
     * @return tx_t3sportstats_srv_Statistics
     */
    public static function getStatisticService()
    {
        return tx_rnbase_util_Misc::getService('t3sportstats', 'statistics');
    }
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3sportstats/util/class.tx_t3sportstats_util_ServiceRegistry.php']) {
    include_once $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3sportstats/util/class.tx_t3sportstats_util_ServiceRegistry.php'];
}
