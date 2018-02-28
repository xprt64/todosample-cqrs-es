<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\CodeAnalysis\Shared\ClassSorter;


use Gica\CodeAnalysis\Shared\ClassComparison\SubclassComparator;
use Gica\CodeAnalysis\Shared\ClassSorter;

class TopologySorter implements ClassSorter
{
    private $cache = [];

    /**
     * @param \ReflectionClass[] $classes
     * @return \ReflectionClass[]
     * @throws \Exception
     */
    public function sortClasses($classes)
    {
        if (count($classes) <= 1) {
            return $classes;
        }

        $input = $this->createTSortInputString($classes);

        $file = tempnam(sys_get_temp_dir(), 'tsort');

        if (false === file_put_contents($file, $input)) {
            throw new \Exception("file_put_contents $file error");
        }

        exec("tsort $file", $sortedClassNames, $returnVar);

        unlink($file);

        if ($returnVar != 0) {
            throw new \Exception("tsort returned $returnVar for $input");
        }

        $sortedClassNames = array_reverse($sortedClassNames);

        usort($classes, function (\ReflectionClass $a, \ReflectionClass $b) use ($sortedClassNames) {
            return array_search($a->name, $sortedClassNames) <=> array_search($b->name, $sortedClassNames);
        });

        return $classes;
    }

    public function doesClassDependsOnClass(\ReflectionClass $consumerClass, \ReflectionClass $consumedClass): bool
    {
        $dependencies = $this->getClassDependencies($consumerClass);

        return $this->isParentClassOfAny($consumedClass, $dependencies);
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param int $level
     * @return \ReflectionClass[]
     */
    private function getClassDependencies(\ReflectionClass $reflectionClass, int $level = 0)
    {
        if (!isset($this->cache[$reflectionClass->name])) {
            $this->cache[$reflectionClass->name] = $this->_getClassDependencies($reflectionClass, $level);
        }

        return $this->cache[$reflectionClass->name];
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param int $level
     * @return \ReflectionClass[]
     */
    private function _getClassDependencies(\ReflectionClass $reflectionClass, int $level = 0)
    {
        $dependencies = [];

        if ($level > 5) {
            return $dependencies;
        }

        $constructor = $reflectionClass->getConstructor();
        if ($constructor && $constructor->getParameters()) {
            $dependencies = array_merge($dependencies, $this->classFromParameters($constructor->getParameters()));
        }

        if ($reflectionClass->getParentClass()) {
            $dependencies = array_merge($dependencies, $this->getClassDependencies($reflectionClass->getParentClass()));
        }

        foreach ($dependencies as $dependency) {
            $dependencies = array_merge($dependencies, $this->getClassDependencies($dependency, $level + 1));
        }

        return $dependencies;
    }

    private function isParentClassOfAny(\ReflectionClass $parentClass, $classes): bool
    {
        $comparator = new SubclassComparator();

        $isASubClassOrSameClass = function (\ReflectionClass $class) use ($parentClass, $comparator) {
            return $comparator->isASubClassOrSameClass($class, $parentClass->name);
        };

        $filtered = array_filter($classes, $isASubClassOrSameClass);

        return count($filtered) > 0;

    }

    /**
     * @param \ReflectionParameter $parameter
     * @return \ReflectionClass
     */
    private function classFromParameter(\ReflectionParameter $parameter)
    {
        return $parameter->getClass();
    }

    /**
     * @param \ReflectionParameter[] $parameters
     * @return \ReflectionClass[]
     */
    private function classFromParameters(array $parameters)
    {
        $strings = array_map(function (\ReflectionParameter $parameter) {
            return $this->classFromParameter($parameter);
        }, $parameters);

        return array_filter($strings, function ($s) {
            return !!$s;
        });
    }

    /**
     * @param \ReflectionClass[] $classes
     * @return string
     */
    private function createTSortInputString($classes): string
    {
        $inputLines = [];
        foreach ($classes as $class) {
            $deps = $this->getClassDependencies($class);
            foreach ($deps as $dep) {
                $inputLines[] = $class->name . ' ' . $dep->name;

            }
        }

        $input = implode("\n", $inputLines);
        return $input;
    }
}