<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Serialize\ObjectHydrator\ObjectUnserializer\WithStaticFactory;


use Gica\Serialize\ObjectHydrator\Exception\ValueNotUnserializable;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer;

class FromString implements ObjectUnserializer
{

    /**
     * @inheritdoc
     */
    public function tryToUnserializeValue(string $objectClassName, $serializedValue)
    {
        if ((is_string($serializedValue) || is_callable([$serializedValue, '__toString']))) {
            if (is_callable([$objectClassName, 'fromString'])) {

                return call_user_func([$objectClassName, 'fromString'], (string)$serializedValue);
            }
        }

        throw  new ValueNotUnserializable();
    }
}