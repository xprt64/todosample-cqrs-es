<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib\Pipe\ArrayPipe;


class ArrayLimit
{
    /**
     * @var int
     */
    private $numberOfItemsToLimitTo;

    public function __construct(int $numberOfItemsToLimitTo)
    {
        $this->numberOfItemsToLimitTo = $numberOfItemsToLimitTo;
    }

    /**
     * @param \Traversable|array $array
     * @return array
     */
    function __invoke($array)
    {
        $result = [];

        $index = 0;

        foreach ($array as $k => $item) {
            if ($index >= $this->numberOfItemsToLimitTo) {
                break;
            }
            $index++;

            $result[$k] = $item;
        }

        return $result;
    }
}