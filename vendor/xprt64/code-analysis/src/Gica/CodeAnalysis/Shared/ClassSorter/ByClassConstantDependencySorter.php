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

    public function __invoke(\ReflectionClass $a, \ReflectionClass $b)
    {
        return $this->getClassConstant($a) <=> $this->getClassConstant($b);
    }

    private function getClassConstant(\ReflectionClass $aClass)
    {
        return $aClass->getConstant($this->constantName);
    }
}