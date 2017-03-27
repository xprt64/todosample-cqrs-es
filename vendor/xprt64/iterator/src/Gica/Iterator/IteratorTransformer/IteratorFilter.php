<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Iterator\IteratorTransformer;


class IteratorFilter
{
    /**
     * @var callable
     */
    protected $filter;

    public function __construct(callable $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @param \Traversable|array $inputIterator
     * @return \Generator
     */
    function __invoke($inputIterator)
    {
        foreach ($inputIterator as $k => $item) {
            if (($this->filter)($item)) {
                yield $item;
            }
        }
    }
}