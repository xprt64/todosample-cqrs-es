<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Serialize\ObjectSerializer;


use Gica\Serialize\ObjectSerializer\Exception\ValueNotSerializable;

class ObjectSerializer
{
    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(
        Serializer $serializer
    )
    {
        $this->serializer = $serializer;
    }

    public function convert($anything)
    {
        if (is_array($anything)) {
            return array_map(function ($item) {
                return $this->convert($item);
            }, $anything);
        }

        if (!is_object($anything)) {
            return $anything;
        }

        try {
            return $this->serializer->serialize($anything);
        } catch (ValueNotSerializable $exception) {
            //continue normally
        }

        $class = new \ReflectionClass($anything);

        $properties = $class->getProperties();

        $result = [
            '@classes' => [],
        ];

        foreach ($properties as $property) {
            /** @var \ReflectionProperty $property */
            $property->setAccessible(true);
            $unserializedValue = $property->getValue($anything);

            $value = $unserializedValue;

            if (is_object($unserializedValue) || is_array($unserializedValue)) {
                $value = $this->convert($unserializedValue);
            }

            $result[$property->getName()] = $value;
            if (is_object($unserializedValue)) {
                $result['@classes'][$property->getName()] = get_class($unserializedValue);
            }
        }

        if (empty($result['@classes'])) {
            unset($result['@classes']);
        }

        return $result;
    }

}