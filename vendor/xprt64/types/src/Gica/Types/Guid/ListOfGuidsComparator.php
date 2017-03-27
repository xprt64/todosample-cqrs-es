<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Types\Guid;


use Gica\Lib\Comparator\ArrayComparator;
use Gica\Types\Guid;

class ListOfGuidsComparator
{
    /**
     * @param Guid[] $operand1
     * @param Guid[] $operand2
     * @return bool
     */
    public function equals(array $operand1, array $operand2):bool
    {
        return (new ArrayComparator())->equals(function (Guid $a, Guid $b) {
            return $a->equals($b);
        }, $operand1, $operand2);
    }
}