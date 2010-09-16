<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Rene Nitzsche
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

require_once(t3lib_extMgm::extPath('rn_base') . 'class.tx_rnbase.php');

tx_rnbase::load('tx_rnbase_util_SearchBase');


/**
 * Class to search player stats from database
 * 
 * @author Rene Nitzsche
 */
class tx_t3sportstats_search_PlayerStats extends tx_rnbase_util_SearchBase {

	protected function getTableMappings() {
		$tableMapping['PLAYERSTAT'] = 'tx_t3sportstats_players';
		$tableMapping['PLAYER'] = 'tx_cfcleague_profiles';
		$tableMapping['MATCH'] = 'tx_cfcleague_games';
		$tableMapping['COMPETITION'] = 'tx_cfcleague_competition';
		$tableMapping['CLUB'] = 'tx_cfcleague_club';
		$tableMapping['CLUBOPP'] = 'tx_cfcleague_club';
		// Hook to append other tables
		tx_rnbase_util_Misc::callHook('t3sportstats','search_PlayerStats_getTableMapping_hook',
			array('tableMapping' => &$tableMapping), $this);
		return $tableMapping;
	}

	protected function useAlias() {return true;}
	protected function getBaseTableAlias() {
		return 'PLAYERSTAT';
	}
	protected function getBaseTable() {
		return 'tx_t3sportstats_players';
	}
	function getWrapperClass() {
		return 'tx_t3sportstats_models_PlayerStat';
	}

	protected function getJoins($tableAliases) {
		$join = '';
		if(isset($tableAliases['MATCH'])) {
			$join .= ' JOIN tx_cfcleague_games AS MATCH ON PLAYERSTAT.t3match = MATCH.uid ';
		}
		if(isset($tableAliases['PLAYER'])) {
			$join .= ' JOIN tx_cfcleague_profiles AS PLAYER ON PLAYERSTAT.player = PLAYER.uid ';
		}
		if(isset($tableAliases['COMPETITION'])) {
			$join .= ' JOIN tx_cfcleague_competition AS COMPETITION ON COMPETITION.uid = PLAYERSTAT.competition ';
		}
		if(isset($tableAliases['CLUB'])) {
			$join .= ' JOIN tx_cfcleague_club AS CLUB ON CLUB.uid = PLAYERSTAT.club ';
		}
		if(isset($tableAliases['CLUBOPP'])) {
			$join .= ' JOIN tx_cfcleague_club AS CLUBOPP ON CLUBOPP.uid = PLAYERSTAT.clubopp ';
		}
		
		// Hook to append other tables
		tx_rnbase_util_Misc::callHook('t3sportstats','search_PlayerStats_getJoins_hook',
			array('join' => &$join, 'tableAliases' => $tableAliases), $this);
		return $join;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3sportstats/search/class.tx_t3sportstats_search_PlayerStats.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3sportstats/search/class.tx_t3sportstats_search_PlayerStats.php']);
}

?>