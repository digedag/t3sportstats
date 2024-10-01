<?php

namespace System25\T3sports\Model;

use Sys25\RnBase\Domain\Model\BaseModel;
use System25\T3sports\Series\SeriesBag;

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
 * Model for a match series result record.
 */
class SeriesResult extends BaseModel
{
    public const TYPE_BEST = 'best';
    public const TYPE_CURRENT = 'current';
    private $fixtures = [];

    public function getTableName()
    {
        return 'tx_t3sportstats_series_result';
    }

    public function getQuantity(): int
    {
        return $this->getProperty('quantity');
    }

    public function getUniqueKey(): ?string
    {
        return $this->getProperty('uniquekey');
    }

    public function setTypeBest(): void
    {
        $this->setProperty('resulttype', self::TYPE_BEST);
    }

    public function setTypeCurrent(): void
    {
        $this->setProperty('resulttype', self::TYPE_CURRENT);
    }

    public function isTypeBest(): bool
    {
        return $this->getProperty('resulttype') === self::TYPE_BEST;
    }

    public function isTypeCurrent(): bool
    {
        return $this->getProperty('resulttype') === self::TYPE_CURRENT;
    }

    public function setFixtures(array $fixtures): void
    {
        $this->fixtures = $fixtures;
    }

    /**
     * 
     * @return Fixtures[]
     */
    public function getFixtures(): array
    {
        return $this->fixtures;
    }
}
