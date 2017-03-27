<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Iterator\IteratorTransformer;


class IteratorLimit
{
    /**
     * @var int
     */
    private $numberOfItemsToLimitTo;

    public function __construct(int $numberOfItemsToLimitTo)
    {
        $this->numberOfItemsToLimitTo = $numberOfItemsToLimitTo;
    }

    function __invoke($inputIterator)
    {
        $index = 0;

        foreach ($inputIterator as $k => $item) {
            if ($index >= $this->numberOfItemsToLimitTo) {
                break;
            }
            $index++;

            yield $item;
        }
    }
}