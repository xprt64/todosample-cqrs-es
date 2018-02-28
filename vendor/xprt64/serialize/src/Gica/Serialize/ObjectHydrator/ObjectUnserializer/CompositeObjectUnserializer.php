<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Serialize\ObjectHydrator\ObjectUnserializer;


use Gica\Serialize\ObjectHydrator\Exception\AdapterNotFoundException;
use Gica\Serialize\ObjectHydrator\Exception\ValueNotUnserializable;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer;

class CompositeObjectUnserializer implements ObjectUnserializer
{

    /**
     * @var ObjectUnserializer[]
     */
    private $unserializers;

    public function __construct(
        array $unserializers = []
    )
    {
        $this->unserializers = $unserializers;
    }

    public function tryToUnserializeValue(string $objectClassName, $serializedValue)
    {
        foreach ($this->unserializers as $adapterLocator) {
            try {
                return $adapterLocator->tryToUnserializeValue($objectClassName, $serializedValue);
            } catch (AdapterNotFoundException $exception) {
                continue;
            } catch (ValueNotUnserializable $exception) {
                continue;
            }
        }

        throw new AdapterNotFoundException(
            sprintf("None of the %d adapter locators could find an adapter", count($this->unserializers)));
    }
}