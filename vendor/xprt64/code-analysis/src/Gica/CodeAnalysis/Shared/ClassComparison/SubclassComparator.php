<?php


namespace Gica\CodeAnalysis\Shared\ClassComparison;


class SubclassComparator
{
    private function isASubClass($object, string $parentClass)
    {
        $parent = new \ReflectionClass($parentClass);
        $child = new \ReflectionClass($object);

        return $parent->isInterface() ? $child->implementsInterface($parentClass) : $child->isSubclassOf($parentClass);
    }

    public function isASubClassOrSameClass($object, string $parentClass)
    {
        return $this->getObjectClass($object) === $parentClass || $this->isASubClass($object, $parentClass);
    }

    public function isASubClassButNoSameClass($object, string $parentClass)
    {
        return $this->getObjectClass($object) !== $parentClass && $this->isASubClass($object, $parentClass);
    }

    private function getObjectClass($object): string
    {
        return is_string($object)
            ? $object
            : ($object instanceof \ReflectionClass ? $object->getName() : get_class($object));
    }
}