<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Iterator\IteratorTransformer;


class IteratorMapper
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
     * @param \Traversable|array $inputIterator
     * @return \Generator
     */
    function __invoke($inputIterator)
    {
        foreach ($inputIterator as $k => $item) {
            yield ($this->callback)($item);
        }
    }

    function asArray($inputIterator):array
    {
        return iterator_to_array($this->__invoke($inputIterator));
    }
}