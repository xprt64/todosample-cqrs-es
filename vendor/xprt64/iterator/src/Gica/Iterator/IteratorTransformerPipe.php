<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Iterator;


class IteratorTransformerPipe implements \IteratorAggregate
{
    private $generators = [];

    private $start;

    /**
     * @param array|\Traversable|null $start
     */
    public function __construct($start = null)
    {
        if (null !== $start) {
            $this->setInputIterator($start);
        }
    }

    /**
     * @param array|\Traversable $start
     */
    public function setInputIterator($start)
    {
        if ($this->isInputIterable($start)) {
            throw new \InvalidArgumentException('$start is not an array or \Traversable');
        }

        $this->start = $start;
    }

    public function pipe($generator)
    {
        $this->generators[] = $generator;

        return $this;
    }


    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return \Iterator An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return $this->transformIterator($this->start);
    }

    /**
     * @param array|\Traversable $inputIterator
     * @return \Iterator|\Traversable
     */
    public function transformIterator($inputIterator)
    {
        if ($this->isInputIterable($inputIterator)) {
            throw new \InvalidArgumentException('$inputIterator is not an array or \Traversable');
        }

        $result = $this->arrayToGenerator($inputIterator);

        foreach ($this->generators as $generator) {
            $result = $generator($result);
        }

        return $result;
    }

    function arrayToGenerator($array)
    {
        foreach ($array as $item) {
            yield $item;
        }
    }

    public function asArray():array
    {
        return iterator_to_array($this->getIterator());
    }

    private function isInputIterable($start):bool
    {
        return !is_array($start) && !($start instanceof \Traversable);
    }
}