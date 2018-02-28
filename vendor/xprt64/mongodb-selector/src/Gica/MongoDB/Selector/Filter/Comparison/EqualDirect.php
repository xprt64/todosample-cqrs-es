<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\MongoDB\Selector\Filter\Comparison;


use Gica\Selector\Filter;

class EqualDirect implements Filter
{
    private $fieldName;
    private $value;

    public function __construct(string $fieldName, $value)
    {
        $this->fieldName = $fieldName;
        $this->value = $value;
    }

    public function applyFilter(array $fields):array
    {
        $fields[$this->fieldName] = $this->value;

        return $fields;
    }
}