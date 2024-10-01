<?php

namespace System25\T3sports\Marker;

use Sys25\RnBase\Frontend\Marker\BaseMarker;
use Sys25\RnBase\Frontend\Marker\Templates;
use Sys25\RnBase\Utility\Misc;
use System25\T3sports\Frontend\Marker\ProfileMarker;
use System25\T3sports\Model\CoachStat;
use System25\T3sports\Model\Profile;
use tx_rnbase;
use tx_rnbase_util_FormatUtil;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2022 Rene Nitzsche (rene@system25.de)
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
 * Diese Klasse ist für die Erstellung von Markerarrays verantwortlich.
 */
class CoachStatsMarker extends BaseMarker
{
    /**
     * @param string $template das HTML-Template
     * @param CoachStat $item
     * @param tx_rnbase_util_FormatUtil $formatter der zu verwendente Formatter
     * @param string $confId Pfad der TS-Config des Spiels, z.B. 'listView.match.'
     * @param string $marker Name des Markers für ein Spiel, z.B. MATCH
     *
     * @return string das geparste Template
     */
    public function parseTemplate($template, $item, &$formatter, $confId, $marker = 'MATCH')
    {
        if (!is_object($item)) {
            return $formatter->getConfigurations()->getLL('item_notFound');
        }
        $this->prepareFields($item, $template, $marker);
        Misc::callHook('t3sportstats', 'coachStatsMarker_initRecord', [
            'item' => &$item,
            'template' => &$template,
            'confid' => $confId,
            'marker' => $marker,
            'formatter' => $formatter,
        ], $this);

        // Das Markerarray wird gefüllt
        $ignore = self::findUnusedCols($item->getProperties(), $template, $marker);
        $markerArray = $formatter->getItemMarkerArrayWrapped($item->getProperties(), $confId, $ignore, $marker.'_');
        $wrappedSubpartArray = [];
        $subpartArray = [];

        $this->prepareLinks($item, $marker, $markerArray, $subpartArray, $wrappedSubpartArray, $confId, $formatter, $template);
        // Es wird jetzt das Template verändert und die Daten der Teams eingetragen

        if ($this->containsMarker($template, $marker.'_COACH_')) {
            $template = $this->_addCoach($template, $item, $formatter, $confId.'coach.', $marker.'_COACH');
        }

        $template = Templates::substituteMarkerArrayCached($template, $markerArray, $subpartArray, $wrappedSubpartArray);
        Misc::callHook('t3sportstats', 'coachStatsMarker_afterSubst', [
            'item' => &$item,
            'template' => &$template,
            'confid' => $confId,
            'marker' => $marker,
            'formatter' => $formatter,
        ], $this);

        return $template;
    }

    /**
     * Bindet den Spieler ein.
     *
     * @param string $template
     * @param CoachStat $item
     * @param tx_rnbase_util_FormatUtil $formatter
     * @param string $confId
     * @param string $markerPrefix
     *
     * @return string
     */
    protected function _addCoach($template, &$item, &$formatter, $confId, $markerPrefix)
    {
        $sub = $item->getCoachUid();
        if (!$sub) {
            // Kein Stadium vorhanden. Leere Instanz anlegen und altname setzen
            $sub = BaseMarker::getEmptyInstance(Profile::class);
        } else {
            $sub = Profile::getProfileInstance($sub);
        }
        $marker = tx_rnbase::makeInstance(ProfileMarker::class);
        $template = $marker->parseTemplate($template, $sub, $formatter, $confId, $markerPrefix);

        return $template;
    }

    /**
     * Im folgenden werden einige Personenlisten per TS aufbereitet.
     * Jede dieser Listen
     * ist über einen einzelnen Marker im FE verfügbar. Bei der Ausgabe der Personen
     * werden auch vorhandene MatchNotes berücksichtigt, so daß ein Spieler mit gelber
     * Karte diese z.B. neben seinem Namen angezeigt bekommt.
     *
     * @param CoachStat $item
     */
    private function prepareFields($item, $template, $markerPrefix)
    {
        $perMatch = [];
        foreach ($item->getProperty() as $key => $value) {
            if (self::containsMarker($template, $markerPrefix.'_'.strtoupper($key).'_PER_MATCH')) {
                $perMatch[$key.'_per_match'] = intval($item->getProperty('played')) ? intval($item->getProperty($key)) / intval($item->getProperty('played')) : 0;
            }
            if (self::containsMarker($template, $markerPrefix.'_'.strtoupper($key).'_AFTER_MINUTES')) {
                $perMatch[$key.'_after_minutes'] = (intval($item->getProperty($key))) ? intval($item->getProperty('playtime')) / intval($item->getProperty($key)) : 0;
            }
        }
        $item->setProperty(array_merge($item->getProperty(), $perMatch));
    }

    /**
     * Links vorbereiten.
     *
     * @param CoachStat $item
     * @param string $marker
     * @param array $markerArray
     * @param array $wrappedSubpartArray
     * @param string $confId
     * @param tx_rnbase_util_FormatUtil $formatter
     */
    private function prepareLinks($item, $marker, &$markerArray, &$subpartArray, &$wrappedSubpartArray, $confId, &$formatter, $template)
    {
        // Verlinkung auf Spielplan mit den Spielen der aktuellen Auswertung
        $linkNames = $formatter->getConfigurations()->getKeyNames($confId.'links.');
        foreach ($linkNames as $linkId) {
            if ($item->getProperty($linkId)) {
                // Link nur bei Wert größer 0 ausführen, damit keine leere Liste verlinkt wird.
                $params = [
                    'statskey' => $linkId,
                    'coach' => $item->getProperty('coach'),
                ];
                $this->initLink($markerArray, $subpartArray, $wrappedSubpartArray, $formatter, $confId, $linkId, $marker, $params, $template);
            } else {
                $linkMarker = $marker.'_'.strtoupper($linkId).'LINK';
                $remove = intval($formatter->configurations->get($confId.'links.'.$linkId.'.removeIfDisabled'));
                $this->disableLink($markerArray, $subpartArray, $wrappedSubpartArray, $linkMarker, $remove > 0);
            }
        }
    }
}
