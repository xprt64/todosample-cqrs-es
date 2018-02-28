<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\CodeAnalysis\Shared;


interface ClassSorter
{
    /**
     * @param \ReflectionClass[] $classes
     * @return \ReflectionClass[]
     */
    public function sortClasses($classes);
}