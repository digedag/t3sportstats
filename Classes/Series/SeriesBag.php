<?php

namespace System25\T3sports\Series;

use System25\T3sports\Model\Club;
use System25\T3sports\Model\Fixture;

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
 *
 */
class SeriesBag
{
    private $bestSeries = [];
    private $currentSeries = [];
    private $club;

    public function __construct(Club $club)
    {
        $this->club = $club;
    }

    public function appendToSeries(Fixture $match)
    {
        $this->currentSeries[] = $match;
    }

    public function breakSeries()
    {
        if (count($this->currentSeries) > count($this->bestSeries)) {
            $this->bestSeries = $this->currentSeries;
        }
        $this->currentSeries = [];
    }

    /**
     * 
     * @return Fixture[]
     */
    public function getBestSeries(): array
    {
        return $this->bestSeries;
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
        return $this->bestSeries[0] ?? null;
    }

    public function getLastMatch(): ?Fixture
    {
        return $this->bestSeries[$this->getSize() - 1] ?? null;
    }

    public function getSize(): int
    {
        return count($this->bestSeries);
    }

    public function getHash(): string
    {
        $uids = [];

        foreach($this->bestSeries as $fixture) {
            $uids[] = $fixture->getUid();
        }

        return md5(implode(',', $uids));
    }
}
