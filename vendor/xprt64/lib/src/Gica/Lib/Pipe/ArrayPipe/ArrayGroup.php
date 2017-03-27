<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib\Pipe\ArrayPipe;


class ArrayGroup
{
    /**
     * @var callable
     */
    protected $callback;

    public function __construct(callable $extractGroupFromItemCallback)
    {
        $this->callback = $extractGroupFromItemCallback;
    }

    function __invoke($array)
    {
        $result = [];

        foreach ($array as $item) {
            $group = call_user_func($this->callback, $item);

            $result[$group][] = $item;
        }

        return $result;
    }
}