<?php

namespace System25\T3sports\Series;

use Contrib\Doctrine\Common\Collections\ArrayCollection;
use Contrib\Doctrine\Common\Collections\Collection;
use System25\T3sports\Model\Club;
use System25\T3sports\Model\Fixture;
use System25\T3sports\Model\Series;
use System25\T3sports\Model\SeriesResult;

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
 * Sammel-Container für Serien bei der Ermittlung.
 */
class SeriesBag
{
    private $bestSeries = [];
    private $currentSeries = [];
    private $club;
    private $bestBagSize;

    public function __construct(Club $club, int $bestBagSize = 3)
    {
        $this->club = $club;
        $this->bestBagSize = $bestBagSize ?: 1;
    }

    public function appendToSeries(Fixture $match)
    {
        $this->currentSeries[] = $match;
    }

    public function breakSeries()
    {
        if (count($this->currentSeries) < 2) {
            $this->currentSeries = [];

            return;
        }
        // Die Serie wird übernommen, wenn
        // wir noch Platz haben,
        // sie länger ist, als die bisher kürzeste Serie
        // Am Ende sortieren
        if (count($this->bestSeries) < $this->bestBagSize) {
            $this->bestSeries[] = $this->currentSeries;
        } elseif (count($this->currentSeries) > count($this->bestSeries[$this->bestBagSize - 1])) {
            $this->bestSeries[] = $this->currentSeries;
        } else {
            $this->currentSeries = [];

            return;
        }
        usort($this->bestSeries, function ($a, $b) {
            $ca = count($a);
            $cb = count($b);
            if ($ca === $cb) {
                return 0;
            }

            return $ca < $cb ? 1 : -1;
        });
        $this->bestSeries = array_slice($this->bestSeries, 0, $this->bestBagSize);
        $this->currentSeries = [];
    }

    /**
     * @return Fixture[]
     */
    public function getBestSeriesFixtures(): array
    {
        return $this->bestSeries[0] ?? [];
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function getClubUid(): int
    {
        return $this->club->getUid();
    }

    public function getFirstMatch(): ?Fixture
    {
        return $this->bestSeries[0][0] ?? null;
    }

    public function getLastMatch(): ?Fixture
    {
        return $this->bestSeries[0][$this->getSize() - 1] ?? null;
    }

    public function getSize(): int
    {
        return count($this->bestSeries[0]);
    }

    private function getHash(array $fixtures): string
    {
        $uids = [];

        foreach ($fixtures as $fixture) {
            $uids[] = $fixture->getUid();
        }

        return md5(implode(',', $uids));
    }

    /**
     * @param Series $series
     * @return Collection<SeriesResult>
     */
    public function getBestSeriesResults(Series $series): Collection
    {
        $result = new ArrayCollection();
        foreach ($this->bestSeries as $bestSeries) {
            $resultSeries = $this->buildSeriesResult($series, $bestSeries);
            if ($resultSeries) {
                $resultSeries->setTypeBest();
                $result->add($resultSeries);
            }
        }

        return $result;
    }

    public function getCurrentSeriesResult(Series $series): ?SeriesResult
    {
        $result = $this->buildSeriesResult($series, $this->currentSeries);
        if ($result) {
            $result->setTypeCurrent();
        }

        return $result;
    }

    private function buildSeriesResult(Series $series, array $fixtures): ?SeriesResult
    {
        if (empty($fixtures)) {
            return null;
        }

        $result = new SeriesResult();
        $size = count($fixtures);
        $result->setProperty('club', $this->getClubUid());
        $result->setProperty('firstmatch', $fixtures[0]->getUid());
        $result->setProperty('lastmatch', $fixtures[$size - 1]->getUid());
        $result->setProperty('quantity', $size);
        $result->setProperty('pid', $series->getPid());
        $result->setProperty('parentid', $series->getUid());
        $result->setProperty('parenttable', 'tx_t3sportstats_series');
        $result->setProperty('uniquekey', $this->getHash($fixtures));

        $result->setFixtures($fixtures);

        return $result;
    }
}
