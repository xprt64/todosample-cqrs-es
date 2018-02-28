<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\MongoDB\Selector\Filter\Logical;

class AndGroup extends CompositeFilter
{
    protected function getToken(): string
    {
        return '$and';
    }
}