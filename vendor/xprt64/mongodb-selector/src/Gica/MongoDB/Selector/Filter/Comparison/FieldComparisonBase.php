<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\MongoDB\Selector\Filter\Comparison;


use Gica\Selector\Filter;

abstract class FieldComparisonBase implements Filter
{
    private $fieldName;
    private $value;
    private $operator;

    public function __construct(string $fieldName, $operator, $value)
    {
        $this->fieldName = $fieldName;
        $this->value = $value;
        $this->operator = $operator;
    }

    public function applyFilter(array $fields): array
    {
        $fields[$this->fieldName] = [
            $this->operator => $this->value,
        ];

        return $fields;
    }
}