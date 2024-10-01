<?php

namespace System25\T3sports\Model;

use Exception;
use Sys25\RnBase\Domain\Model\BaseModel;
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
 * Model for a match series rule record.
 */
class SeriesRule extends BaseModel
{
    private $rule;

    public function getTableName()
    {
        return 'tx_t3sportstats_series_rule';
    }

    public function setRule(SeriesRuleInterface $rule)
    {
        $this->rule = $rule;
    }

    public function isSatisfied(Fixture $match, bool $isHome): bool
    {
        return $this->getRule()->isSatified($match, $isHome, $this->getProperty('config'));
    }

    private function getRule(): SeriesRuleInterface
    {
        if (null === $this->rule) {
            throw new Exception('Missing rule implementation for '.$this->getUid());
        }

        return $this->rule;
    }
}