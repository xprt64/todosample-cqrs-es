<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Serialize\ObjectHydrator\ObjectUnserializer\WithStaticFactory;


use Gica\Serialize\ObjectHydrator\Exception\ValueNotUnserializable;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer;

class FromPrimitive implements ObjectUnserializer
{

    /**
     * @inheritdoc
     */
    public function tryToUnserializeValue(string $objectClassName, $serializedValue)
    {
        if (is_scalar($serializedValue) || is_array($serializedValue)) {
            if (is_callable([$objectClassName, 'fromPrimitive'])) {
                return call_user_func([$objectClassName, 'fromPrimitive'], $serializedValue);
            }
        }

        throw  new ValueNotUnserializable();
    }
}