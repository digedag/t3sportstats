<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2016 Rene Nitzsche
 *  Contact: rene@system25.de
 *  All rights reserved
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 ***************************************************************/

tx_rnbase::load('tx_rnbase_util_SearchBase');


/**
 * Mit dem Builder werden haufig auftretende Suchanfragen zusammengebaut
 *
 * @author Rene Nitzsche
 */
class tx_t3sportstats_search_Builder {

	/**
	 * Search for player statistics by scope
	 *
	 * @param array $fields
	 * @param string $scope Scope Array
	 * @return true
	 */
	public static function buildPlayerStatsByScope(&$fields, $scope) {
		$result = false;
		$result = self::setField($fields,'PLAYERSTAT.SAISON', OP_IN_INT, $scope['SAISON_UIDS']) || $result;
		$result = self::setField($fields,'PLAYERSTAT.AGEGROUP', OP_IN_INT, $scope['GROUP_UIDS']) || $result;
		$result = self::setField($fields,'PLAYERSTAT.COMPETITION', OP_IN_INT, $scope['COMP_UIDS']) || $result;
		$result = self::setField($fields,'PLAYERSTAT.CLUB', OP_IN_INT, $scope['CLUB_UIDS']) || $result;
		return true;
	}
	/**
	 * Search for coach statistics by scope
	 *
	 * @param array $fields
	 * @param string $scope Scope Array
	 * @return true
	 */
	public static function buildCoachStatsByScope(&$fields, $scope) {
		$result = false;
		$result = self::setField($fields,'COACHSTAT.SAISON', OP_IN_INT, $scope['SAISON_UIDS']) || $result;
		$result = self::setField($fields,'COACHSTAT.AGEGROUP', OP_IN_INT, $scope['GROUP_UIDS']) || $result;
		$result = self::setField($fields,'COACHSTAT.COMPETITION', OP_IN_INT, $scope['COMP_UIDS']) || $result;
		$result = self::setField($fields,'COACHSTAT.CLUB', OP_IN_INT, $scope['CLUB_UIDS']) || $result;
		return true;
	}
	/**
	 * Search for referee statistics by scope
	 *
	 * @param array $fields
	 * @param string $scope Scope Array
	 * @return true
	 */
	public static function buildRefereeStatsByScope(&$fields, $scope) {
		$result = false;
		$result = self::setField($fields,'REFEREESTAT.SAISON', OP_IN_INT, $scope['SAISON_UIDS']) || $result;
		$result = self::setField($fields,'REFEREESTAT.AGEGROUP', OP_IN_INT, $scope['GROUP_UIDS']) || $result;
		$result = self::setField($fields,'REFEREESTAT.COMPETITION', OP_IN_INT, $scope['COMP_UIDS']) || $result;
		$result = self::setField($fields,'REFEREESTAT.CLUB', OP_IN_INT, $scope['CLUB_UIDS']) || $result;
		return true;
	}
	public static function setField(&$fields, $field, $operator, $value) {
		$result = false;
		if(strlen(trim($value))) {
			$fields[$field][$operator] = $value;
			$result = true;
		}
		return $result;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3sportstats/search/class.tx_t3sportstats_search_Builder.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3sportstats/search/class.tx_t3sportstats_search_Builder.php']);
}
