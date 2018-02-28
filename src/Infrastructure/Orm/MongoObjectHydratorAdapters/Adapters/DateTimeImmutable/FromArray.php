<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Orm\MongoObjectHydratorAdapters\Adapters\DateTimeImmutable;


use Gica\Serialize\ObjectHydrator\ObjectUnserializer;

class FromArray implements ObjectUnserializer
{
    public function tryToUnserializeValue(string $objectClass, $serializedValue)
    {
        return \Gica\Types\DateTimeImmutable::deserialize($serializedValue);
    }
}