<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib\Pipe\ArrayPipe;


class ArrayMapExpand
{
    /**
     * @var callable
     */
    protected $callback;

    public function __construct(callable $callback /* function(item)*/)
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
            $expanded = ($this->callback)($item);
            if (is_array($expanded)) {
                foreach ($expanded as $expandedItem) {
                    $result[] = $expandedItem;
                }
            } else {
                $result[] = $expanded;
            }
        }

        return $result;
    }

}