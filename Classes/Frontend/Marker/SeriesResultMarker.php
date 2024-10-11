<?php

namespace System25\T3sports\Frontend\Marker;

use InvalidArgumentException;
use Sys25\RnBase\Domain\Repository\RepositoryRegistry;
use Sys25\RnBase\Frontend\Marker\BaseMarker;
use Sys25\RnBase\Frontend\Marker\FormatUtil;
use Sys25\RnBase\Frontend\Marker\SimpleMarker;
use System25\T3sports\Model\Fixture;
use System25\T3sports\Model\Series;
use System25\T3sports\Model\SeriesResult;
use tx_rnbase;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2024 Rene Nitzsche (rene@system25.de)
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
class SeriesResultMarker extends SimpleMarker
{
    private $fixtureRepo;
    private $seriesRepo;

    public function __construct($options = [])
    {
        $this->setClassname(SeriesResult::class);
        parent::__construct($options);
        $this->fixtureRepo = RepositoryRegistry::getRepositoryForClass(Fixture::class);
        $this->seriesRepo = RepositoryRegistry::getRepositoryForClass(Series::class);
    }

    /**
     * @param string $template
     * @param SeriesResult $item
     * @param FormatUtil $formatter
     * @param string $confId
     * @param string $marker
     * @return string
     * @throws InvalidArgumentException
     */
    protected function prepareTemplate($template, $item, $formatter, $confId, $marker)
    {
        if (self::containsMarker($template, $marker.'_FIRSTMATCH_')) {
            $template = $this->addFixture($template, $item->getProperty('firstmatch'), $formatter, $confId.'firstmatch.', $marker.'_FIRSTMATCH');
        }
        if (self::containsMarker($template, $marker.'_LASTMATCH_')) {
            $template = $this->addFixture($template, $item->getProperty('lastmatch'), $formatter, $confId.'lastmatch.', $marker.'_LASTMATCH');
        }
        if (self::containsMarker($template, $marker.'_SERIES_')) {
            $template = $this->addSeries($template, $item->getProperty('parentid'), $formatter, $confId.'series.', $marker.'_SERIES');
        }

        return $template;
    }

    /**
     * Bindet ein Spiel ein.
     *
     * @param string $template
     * @param int $fixtureUid
     * @param FormatUtil $formatter
     * @param string $confId
     * @param string $markerPrefix
     *
     * @return string
     */
    private function addFixture($template, $fixtureUid, FormatUtil $formatter, $confId, $markerPrefix)
    {
        if (!$fixtureUid) {
            // Kein Item vorhanden. Leere Instanz anlegen und altname setzen
            $sub = BaseMarker::getEmptyInstance(Fixture::class);
        } else {
            $sub = $this->fixtureRepo->findByUid($fixtureUid);
        }
        $marker = tx_rnbase::makeInstance(MatchMarker::class);
        $template = $marker->parseTemplate($template, $sub, $formatter, $confId, $markerPrefix);

        return $template;
    }

    /**
     * Bindet die Serie ein.
     *
     * @param string $template
     * @param int $fixtureUid
     * @param FormatUtil $formatter
     * @param string $confId
     * @param string $markerPrefix
     *
     * @return string
     */
    private function addSeries($template, $fixtureUid, FormatUtil $formatter, $confId, $markerPrefix)
    {
        if (!$fixtureUid) {
            // Kein Item vorhanden. Leere Instanz anlegen und altname setzen
            $sub = BaseMarker::getEmptyInstance(Series::class);
        } else {
            $sub = $this->seriesRepo->findByUid($fixtureUid);
        }
        $marker = tx_rnbase::makeInstance(SeriesMarker::class);
        $template = $marker->parseTemplate($template, $sub, $formatter, $confId, $markerPrefix);

        return $template;
    }
}
