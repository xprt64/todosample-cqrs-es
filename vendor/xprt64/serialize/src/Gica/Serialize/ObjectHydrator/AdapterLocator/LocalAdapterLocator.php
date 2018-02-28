<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Serialize\ObjectHydrator\AdapterLocator;


use Gica\Serialize\ObjectHydrator\Exception\AdapterNotFoundException;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer;

abstract class LocalAdapterLocator implements ObjectUnserializer
{
    protected function locateUnserializerForClass(string $className, $serializedValue):?ObjectUnserializer
    {
        $hydratorName = $this->getNamespace() . '\\' . $className . '\\From' . ucfirst($this->getNameFromSerializedValue($serializedValue));

        if (class_exists($hydratorName)) {
            return new $hydratorName;
        }

        return null;
    }

    public function tryToUnserializeValue(string $objectClassName, $serializedValue)
    {
        $adapter = $this->locateUnserializerForClass($objectClassName, $serializedValue);

        if ($adapter) {
            return $adapter->tryToUnserializeValue($objectClassName, $serializedValue);
        }

        throw new AdapterNotFoundException(sprintf("Adapter for %s not found", $objectClassName));
    }

    abstract protected function getNamespace(): string;

    /**
     * @param $serializedValue
     * @return string
     */
    private function getNameFromSerializedValue($serializedValue)
    {
        if (is_object($serializedValue)) {
            return $this->getShortClassName($serializedValue);
        }

        return gettype($serializedValue);
    }

    private function getShortClassName($serializedValue): string
    {
        $parts = explode('\\', get_class($serializedValue));

        return array_pop($parts);
    }
}