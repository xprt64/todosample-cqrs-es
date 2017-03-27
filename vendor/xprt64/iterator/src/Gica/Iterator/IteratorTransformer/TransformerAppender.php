<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Iterator\IteratorTransformer;


class TransformerAppender implements \IteratorAggregate
{
    /**
     * @var \Gica\Iterator\IteratorTransformerPipe[]
     */
    private $iteratorTransformers;

    public function __construct(\Gica\Iterator\IteratorTransformerPipe ...$iteratorTransformers)
    {
        $this->iteratorTransformers = $iteratorTransformers;
    }


    public function pipe($iterator)
    {
        foreach ($this->iteratorTransformers as $iteratorTransformer) {
            $iteratorTransformer->pipe($iterator);
        }
        return $this;
    }

    public function asArray():array
    {
        return iterator_to_array($this->getIterator());
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        foreach ($this->iteratorTransformers as $iteratorTransformer) {
            foreach ($iteratorTransformer as $value) {
                yield $value;
            }
        }
    }
}