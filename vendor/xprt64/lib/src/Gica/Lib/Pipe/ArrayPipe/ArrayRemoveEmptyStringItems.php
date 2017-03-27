<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib\Pipe\ArrayPipe;


class ArrayRemoveEmptyStringItems
{
    protected $charsToBeTrimmed = " \t\n\r\0\x0B";

    function __invoke($array)
    {
        if (!is_array($array) && $array instanceof \Traversable) {
            $array = iterator_to_array($array);
        }

        return array_filter($array, function ($item) {
            return '' !== trim("$item", $this->charsToBeTrimmed);
        });
    }

}