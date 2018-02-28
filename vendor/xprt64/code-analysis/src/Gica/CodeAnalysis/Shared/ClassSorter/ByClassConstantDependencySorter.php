<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\CodeAnalysis\Shared\ClassSorter;


use Gica\CodeAnalysis\Shared\ClassSorter;

class ByClassConstantDependencySorter implements ClassSorter
{

    /**
     * @var string
     */
    private $constantName;

    public function __construct(
        string $constantName
    )
    {
        $this->constantName = $constantName;
    }

    private function getClassConstant(\ReflectionClass $aClass)
    {
        return $aClass->getConstant($this->constantName);
    }

    /**
     * @param \ReflectionClass[] $classes
     * @return \ReflectionClass[]
     */
    public function sortClasses($classes)
    {
        usort($classes, function (\ReflectionClass $a, \ReflectionClass $b) {
            return $this->getClassConstant($a) <=> $this->getClassConstant($b);
        });

        return $classes;
    }
}