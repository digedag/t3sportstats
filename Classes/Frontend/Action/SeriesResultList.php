<?php

namespace System25\T3sports\Frontend\Action;

use Sys25\RnBase\Frontend\Controller\AbstractAction;
use Sys25\RnBase\Frontend\Filter\BaseFilter;
use Sys25\RnBase\Frontend\Request\RequestInterface;
use Sys25\RnBase\Frontend\View\Marker\ListView;
use System25\T3sports\Repository\SeriesResultRepository;
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
 * Wir nutzen diesen View für die Anzeige der aktuell laufenden Serien. Diese
 * definieren sich nur das Vorhandensein eines Results vom Typ "current".
 */
class SeriesResultList extends AbstractAction
{
    private $seriesResultRepo;

    public function __construct(?SeriesResultRepository $seriesResultRepo = null)
    {
        $this->seriesResultRepo = $seriesResultRepo ?: tx_rnbase::makeInstance(SeriesResultRepository::class);
    }

    /**
     * @param RequestInterface $request
     *
     * @return string error msg or null
     */
    protected function handleRequest(RequestInterface $request)
    {
        $viewData = $request->getViewContext();

        $filter = BaseFilter::createFilter($request, $this->getConfId().'filter.');
        $fields = [];
        $options = [];
        $filter->init($fields, $options);

        $items = $this->seriesResultRepo->search($fields, $options);

        $viewData->offsetSet('items', $items);

        return null;
    }

    protected function getTemplateName()
    {
        return 'seriesresultlist';
    }

    protected function getViewClassName()
    {
        return ListView::class;
    }
}