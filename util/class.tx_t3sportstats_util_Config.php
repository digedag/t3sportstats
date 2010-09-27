<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Rene Nitzsche (rene@system25.de)
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

require_once(t3lib_extMgm::extPath('rn_base') . 'class.tx_rnbase.php');


/**
 * 
 */
class tx_t3sportstats_util_Config {
	/**
	 * Returns all configured statistics type for flexform
	 * @return array
	 */
	public static function lookupPlayerStatsReport($config) {
		if($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['playerStats']['reports']) {
			$types = t3lib_div::trimExplode(',',$GLOBALS ['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['playerStats']['reports']);
			foreach ($types As $type) {
				$config['items'][] = array($type, $type);
			}
		}
		return $config;
	}
	public static function registerPlayerStatsReport($statsType) {
		$current = array();
		if($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['playerStats']['reports']) {
			$current = array_flip(t3lib_div::trimExplode(',',$GLOBALS ['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['playerStats']['reports']));
		}
		if(!array_key_exists($statsType, $current)) {
			$current = array_flip($current);
			$current[] = $statsType;
			$GLOBALS ['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['playerStats']['reports'] = implode(',', $current);
		}
	}
	/**
	 * Register a new simple statistics.
	 * @param string $column
	 * @param mixed $types commaseparated list of match event uids
	 */
	public static function registerPlayerStatsSimple($column, $types) {
		$column = strtolower($column);
		if(!is_array($GLOBALS ['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['playerStats']['simpleStats']))
			$GLOBALS ['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['playerStats']['simpleStats'] = array();

		if(!array_key_exists($column, $GLOBALS ['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['playerStats']['simpleStats'])) {
			$GLOBALS ['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['playerStats']['simpleStats'][$column] = array(
				'types' => $types,
			);
		}
		else {
			$GLOBALS ['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['playerStats']['simpleStats'][$column] .= ','.$types;
		}
	}
	/**
	 * Returns all registered simple statistics
	 * @return array
	 */
	public static function getPlayerStatsSimple() {
		return $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportstats']['playerStats']['simpleStats'];
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3sportstats/util/class.tx_t3sportstats_util_Config.php']){
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3sportstats/util/class.tx_t3sportstats_util_Config.php']);
}
?>