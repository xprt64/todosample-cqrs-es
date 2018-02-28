<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\MongoDB\Selector;


class CountByFieldResult
{
    private $value;
    /**
     * @var int
     */
    private $count;

    public function __construct(
        $value,
        int $count
    )
    {
        $this->value = $value;
        $this->count = $count;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function castValue($otherValue)
    {
        if (is_bool($this->value)) {
            return boolval($otherValue);
        } else if (is_integer($this->value)) {
            return (int)$otherValue;
        } else if (is_null($this->value)) {
            return $otherValue ? $otherValue : null;
        }

        return $otherValue;
    }

}