<?php

namespace System25\T3sports\Repository;

use Contrib\Doctrine\Common\Collections\Collection;
use Sys25\RnBase\Domain\Repository\PersistenceRepository;
use System25\T3sports\Model\Club;
use System25\T3sports\Model\Series;
use System25\T3sports\Model\SeriesResult;
use System25\T3sports\Search\SeriesResultSearch;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017-2024 Rene Nitzsche (rene@system25.de)
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
 * @author Rene Nitzsche
 */
class SeriesResultRepository extends PersistenceRepository
{
    public function getSearchClass()
    {
        return SeriesResultSearch::class;
    }

    /**
     * @return Collection<SeriesResult>
     */
    public function findBySeries(Series $series): Collection
    {
        return $this->search(['SERIESRESULT.parentid' => [OP_EQ_INT => $series->getUid()]], []);
    }

    /**
     * @return Collection<SeriesResult>
     */
    public function findBySeriesAndClub(Series $series, Club $club): Collection
    {
        return $this->search([
            'SERIESRESULT.parentid' => [OP_EQ_INT => $series->getUid()],
            'SERIESRESULT.club' => [OP_EQ_INT => $club->getUid()],
        ], []);
    }

    /**
     * 
     * @param Series $series 
     * @param mixed $clubUid 
     * @return int 
     */
    public function clearSeriesResultByClub(Series $series, $clubUid): int
    {
        $result = 0;
        // Zuerst die Referenzen auf die Spiele entfernen
        $existingSeries = $this->findBySeriesAndClub($series, $clubUid);
        foreach($existingSeries as $existing) {
            $result += $this->clearSeriesResult($existing);
        }

        return $result;
    }

    public function clearSeriesResult(SeriesResult $seriesResult): int
    {
        // Zuerst die Referenzen auf die Spiele entfernen
        $this->getConnection()->doDelete('tx_t3sportstats_series_result_mm', function(QueryBuilder $qb) use ($seriesResult) {
            $qb->where('uid_local = :seriesId')
                ->setParameter('seriesId', $seriesResult->getUid());
        });

        $fields = [
            'SERIESRESULT.uid' => [OP_EQ_INT => $seriesResult->getUid()],
        ];

        return $this->delete($fields);
    }
}
