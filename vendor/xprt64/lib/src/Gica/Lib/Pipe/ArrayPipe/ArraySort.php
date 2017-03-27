<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib\Pipe\ArrayPipe;


class ArraySort
{
    /**
     * @var callable
     */
    protected $callback;

    public function __construct(callable $callback /* function(item1, item2)*/)
    {
        $this->callback = $callback;
    }

    /**
     * @param \Traversable|array $array
     * @return array
     */
    function __invoke($array)
    {
        if ($array instanceof \Traversable)
            $array = iterator_to_array($array);

        uasort($array, $this->callback);

        return $array;
    }

}