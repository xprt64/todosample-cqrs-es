<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\MongoDB\Selector\Filter\Comparison;


use MongoDB\BSON\Regex;

class Regexp extends \Gica\MongoDB\Selector\Filter\Comparison\FieldComparisonBase
{
    public function __construct(string $fieldName, Regex $value)
    {
        parent::__construct($fieldName, '$regex', '/' . $value->getPattern() . '/' . $value->getFlags());
    }
}