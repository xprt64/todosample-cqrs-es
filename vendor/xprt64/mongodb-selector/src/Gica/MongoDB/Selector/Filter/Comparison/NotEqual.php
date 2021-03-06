<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\MongoDB\Selector\Filter\Comparison;


class NotEqual extends \Gica\MongoDB\Selector\Filter\Comparison\FieldComparisonBase
{
    public function __construct(string $fieldName, $value)
    {
        parent::__construct($fieldName, '$ne', $value);
    }
}