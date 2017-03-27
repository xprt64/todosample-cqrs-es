<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Iterator\IteratorTransformer;


class IteratorExpander
{
    /**
     * @var callable
     */
    protected $expanderGenerator;

    public function __construct(callable $expanderGenerator /* function(item){ yield x; yield y}*/)
    {
        $this->expanderGenerator = $expanderGenerator;
    }

    function __invoke($inputIterator)
    {
        foreach ($inputIterator as $k => $item) {
            foreach (($this->expanderGenerator)($item) as $subItem) {
                yield $subItem;
            }
        }
    }
}