<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Serialize\ObjectHydrator;


use Gica\Serialize\ObjectHydrator\Exception\ValueNotUnserializable;

interface ObjectUnserializer
{
    /**
     * @param string $objectClassName
     * @param $serializedValue
     * @return mixed
     * @throws ValueNotUnserializable
     */
     public function tryToUnserializeValue(string $objectClassName, $serializedValue);
}