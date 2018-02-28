<?php


namespace Gica\Serialize\ObjectHydrator\ObjectUnserializer;


use Gica\Serialize\ObjectHydrator\Exception\ValueNotUnserializable;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer;

class DateTimeImmutableFromString implements ObjectUnserializer
{

    /**
     * @param string $objectClassName
     * @param $serializedValue
     * @return mixed
     * @throws ValueNotUnserializable
     */
    public function tryToUnserializeValue(string $objectClassName, $serializedValue)
    {
        if (is_string($serializedValue)) {
            return new \DateTimeImmutable($serializedValue);
        }

        throw new ValueNotUnserializable();
    }
}