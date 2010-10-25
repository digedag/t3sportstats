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
tx_rnbase::load('tx_cfcleague_models_Competition');

/**
 * 
 * @author Rene Nitzsche
 */
class tx_t3sportstats_hooks_MergeProfiles {

	/**
	 * Update der Statistikdaten. Abgleich erfolgt ohne TCE, da die Tabellen nicht in der TCA beschrieben sind. 
	 * @param array $params
	 * @param tx_cfcleague_mod1_profileMerger $parent
	 */
	public function mergeProfile($params, $parent) {
		$leading = $params['leadingUid'];
		$obsolete = $params['obsoleteUid'];
		tx_rnbase_util_DB::doUpdate('tx_t3sportstats_players', 'player='.$obsolete, array('player' => $leading));
		tx_rnbase_util_DB::doUpdate('tx_t3sportstats_coachs', 'coach='.$obsolete, array('coach' => $leading));
		tx_rnbase_util_DB::doUpdate('tx_t3sportstats_referees', 'referee='.$obsolete, array('referee' => $leading));
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3sportstats/hooks/class.tx_t3sportstats_hooks_MergeProfiles.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3sportstats/hooks/class.tx_t3sportstats_hooks_MergeProfiles.php']);
}

?>