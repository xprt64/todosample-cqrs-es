<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib\Pipe\ArrayPipe;


class ArrayImplode
{
    protected $glue;

    public function __construct($glue = ', ')
    {
        $this->glue = $glue;
    }

    /**
     * @param \Traversable|array $array
     * @return string
     */
    function __invoke($array)
    {
        if (!is_array($array)) {
            if ($array instanceof \Traversable) {
                $array = iterator_to_array($array);
            } else {
                return (string)$array;
            }
        }

        return implode($this->glue, $array);
    }
}