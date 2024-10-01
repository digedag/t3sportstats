<?php

namespace System25\T3sports\Series;

use Sys25\RnBase\Utility\Misc;

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
 * Zentrale Klasse für den Zugriff auf verschiedene Services.
 */
class SeriesRuleProvider implements \TYPO3\CMS\Core\SingletonInterface
{
    private $rules = [];

    /**
     * Used by T3 versions without DI.
     *
     * @var SeriesRuleProvider
     */
    private static $instance;

    public function addSeriesRule(SeriesRuleInterface $rule)
    {
        $alias = $rule->getAlias();
        $this->rules[$alias] = $rule;
    }

    /**
     * @return SeriesRuleInterface
     */
    public function getSeriesRuleByAlias(string $alias)
    {
        return $this->rules[$alias] ?? null;
    }

    /**
     * Only used by T3 versions prior to 10.x.
     *
     * @return self
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getRules4Tca(array &$config): void
    {
        $items = [];
        foreach ($this->rules as $rule) {
            $items[] = [
                Misc::translateLLL($rule->getTCALabel()),
                $rule->getAlias(),
            ];
        }

        $config['items'] = $items;
    }
}