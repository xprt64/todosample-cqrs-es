<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib\Pipe\ArrayPipe;


class ArrayMapWithKeys
{
    /**
     * @var callable
     */
    protected $callback;

    public function __construct(callable $callback /* function(item, key)*/)
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
            $result[$k] = ($this->callback)($item, $k);
        }

        return $result;
    }
}