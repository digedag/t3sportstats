<?php

namespace System25\T3sports\Frontend\Marker;

use Sys25\RnBase\Domain\Repository\RepositoryRegistry;
use Sys25\RnBase\Frontend\Marker\FormatUtil;
use Sys25\RnBase\Frontend\Marker\ListBuilder;
use Sys25\RnBase\Frontend\Marker\SimpleMarker;
use Sys25\RnBase\Search\SearchBase;
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
class SeriesMarker extends SimpleMarker
{
    public function __construct($options = [])
    {
        $this->setClassname(Series::class);
        parent::__construct($options);
    }

    protected function prepareTemplate($template, $item, $formatter, $confId, $marker)
    {
        if (self::containsMarker($template, $marker.'_RESULTS')) {
            $template = $this->addResults($template, $item, $formatter, $confId.'result.', $marker.'_RESULT');
        }

        return $template;
    }

    /**
     * Bindet den gefundenen Ergebnisse ein.
     *
     * @param string $template
     * @param Series $item
     * @param FormatUtil $formatter
     * @param string $confId
     * @param string $markerPrefix
     *
     * @return string
     */
    private function addResults($template, $item, $formatter, $confId, $markerPrefix)
    {
        $repo = RepositoryRegistry::getRepositoryForClass(SeriesResult::class);
        $options = $fields = [];
        $fields['SERIESRESULT.PARENTID'][OP_EQ_INT] = $item->getUid();
        $fields['SERIESRESULT.RESULTTYPE'][OP_EQ] = SeriesResult::TYPE_BEST;
        SearchBase::setConfigFields($fields, $formatter->getConfigurations(), $confId.'fields.');
        SearchBase::setConfigOptions($options, $formatter->getConfigurations(), $confId.'options.');
        $children = $repo->search($fields, $options);

        $listBuilder = tx_rnbase::makeInstance(ListBuilder::class);
        $out = $listBuilder->render($children, false, $template, SeriesResultMarker::class, $confId, $markerPrefix, $formatter);

        return $out;

        return $template;
    }
}
