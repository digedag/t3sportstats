<?php

namespace System25\T3sports\Utility;

/***************************************************************
*  Copyright notice
*
*  (c) 2010-2023 Rene Nitzsche (rene@system25.de)
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
 * Data container.
 */
class StatsDataBag
{
    private $data = [];
    private $parent;

    public function setParentUid($uid)
    {
        $this->parent = $uid;
    }

    public function getParentUid()
    {
        return $this->parent;
    }

    public function addType($type, $value)
    {
        $this->data[$type] = intval($this->data[$type] ?? 0) + $value;
    }

    public function setType($type, $value)
    {
        $this->data[$type] = $value;
    }

    public function getTypeValue($type)
    {
        return (int) $this->data[$type];
    }

    public function getTypeValues()
    {
        return $this->data;
    }

    public function __toString()
    {
        $out = "\n\nData:\n";
        foreach ($this->data as $key => $val) {
            $out .= sprintf("%s = %s\n", $key, $val);
        }
        reset($this->data);

        return $out;
    }
}
