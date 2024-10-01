<?php

namespace System25\T3sports\Series\Rule;

use System25\T3sports\Model\Fixture;
use System25\T3sports\Series\SeriesRuleInterface;

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
 * Interface for series rule.
 */
abstract class MatchResultRule implements SeriesRuleInterface
{
    public const RESULT_WIN = 'win';
    public const RESULT_LOST = 'lost';
    public const RESULT_DRAW = 'draw';

    public function getTcaLabel(): string
    {
        return sprintf('LLL:EXT:t3sportstats/Resources/Private/Language/locallang_db.xlf:tx_t3sportstats_series_rule.%s', strtolower($this->getAlias()));
    }

    public function setConfig($config): void
    {
    }

    public function isSatified(Fixture $match, bool $isHome, $config): bool
    {
        $toto = $match->getToto();
        $result = self::RESULT_DRAW;
        if (1 === $toto) {
            $result = $isHome ? self::RESULT_WIN : self::RESULT_LOST;
        } elseif (2 === $toto) {
            $result = $isHome ? self::RESULT_LOST : self::RESULT_WIN;
        }

        return in_array($result, $this->getStates());
    }

    abstract protected function getStates(): array;
}
