<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\MongoDB\Selector;


class CountableIterator implements \Countable, \IteratorAggregate
{
    /**
     * @var Selector
     */
    private $selector;

    public function __construct(
        Selector $selector
    )
    {
        $this->selector = $selector;
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return $this->selector->count();
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return $this->selector->find();
    }
}