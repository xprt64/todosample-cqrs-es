<?php

namespace Gica\CodeAnalysis\Shared\ClassSorter;

use Gica\CodeAnalysis\Shared\ClassSorter;

class AlphabeticalClassSorter implements ClassSorter
{
    /**
     * @param \ReflectionClass[] $classes
     * @return \ReflectionClass[]
     */
    public function sortClasses($classes)
    {
        usort($classes, function (\ReflectionClass $a, \ReflectionClass $b) {
            return $a->name <=> $b->name;
        });

        return $classes;
    }
}