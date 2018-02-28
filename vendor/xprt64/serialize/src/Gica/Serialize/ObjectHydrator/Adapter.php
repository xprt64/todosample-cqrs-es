<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Serialize\ObjectHydrator;


interface Adapter
{
    /**
     * @param string $objectClass
     * @param $serializedValue
     * @return mixed
     * @throws \Exception
     */
    public function tryToUnserialize(string $objectClass, $serializedValue);
}