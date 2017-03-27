<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib\Comparator;


class ArrayComparator
{
    /**
     * @param callable $itemComparator
     * @param array $operand1
     * @param array $operand2
     * @return bool
     */
    public function equals(callable  $itemComparator, array $operand1 = null, array $operand2 = null):bool
    {
        if ($operand1 === null && $operand2 === null) {
            return true;
        }

        if ($operand1 === null && $operand2 !== null) {
            return false;
        }

        if ($operand1 !== null && $operand2 === null) {
            return false;
        }

        if (count($operand1) != count($operand2)) {
            return false;
        }

        $operand1 = array_values($operand1);

        $operand2 = array_values($operand2);

        foreach ($operand1 as $k => $a) {
            $b = $operand2[$k];

            if (!call_user_func($itemComparator, $a, $b)) {
                return false;
            }
        }

        return true;
    }
}