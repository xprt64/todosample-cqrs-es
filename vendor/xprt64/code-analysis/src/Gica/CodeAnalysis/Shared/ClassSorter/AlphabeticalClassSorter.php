<?php
namespace Gica\CodeAnalysis\Shared\ClassSorter;

use Gica\CodeAnalysis\Shared\ClassSorter;

class AlphabeticalClassSorter implements ClassSorter
{

    public function __invoke(\ReflectionClass $a, \ReflectionClass $b)
    {
        return $a->name <=> $b->name;
    }
}