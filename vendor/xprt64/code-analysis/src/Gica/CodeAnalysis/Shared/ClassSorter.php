<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\CodeAnalysis\Shared;


interface ClassSorter
{
    public function __invoke(\ReflectionClass $a, \ReflectionClass $b);
}