<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Serialize\ObjectHydrator;


class ArrayHydrator
{

    /**
     * @var ObjectHydrator
     */
    private $objectHydrator;

    public function __construct(
        ObjectHydrator $objectHydrator
    )
    {
        $this->objectHydrator = $objectHydrator;
    }

    public function hydrateArray(string $objectClass, $serializedValue)
    {
        $result = [];

        $serializedValue = $serializedValue ?: [];

        foreach ($serializedValue as $value) {
            $result[] = $this->objectHydrator->hydrateObject($objectClass, $value);
        }
        return $result;
    }

    public function getObjectHydrator(): ObjectHydrator
    {
        return $this->objectHydrator;
    }
}