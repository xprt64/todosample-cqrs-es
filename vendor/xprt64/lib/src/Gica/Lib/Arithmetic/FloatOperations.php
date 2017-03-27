<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib\Arithmetic;


class FloatOperations
{
    public static function areEqual($operant1, $operand2, float $acceptedError = 0.1)
    {
        return abs($operant1 - $operand2) < $acceptedError;
    }
}