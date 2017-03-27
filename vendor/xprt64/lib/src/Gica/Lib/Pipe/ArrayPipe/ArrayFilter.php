<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib\Pipe\ArrayPipe;


class ArrayFilter
{
    /**
     * @var callable
     */
    protected $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param \Traversable|array $array
     * @return array
     */
    function __invoke($array)
    {
        $result = [];

        foreach ($array as $k => $item) {
            if (($this->callback)($item)) {
                $result[$k] = $item;
            }
        }

        return $result;
    }
}