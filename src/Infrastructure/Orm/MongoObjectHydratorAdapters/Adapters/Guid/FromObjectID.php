<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Orm\MongoObjectHydratorAdapters\Adapters\Guid;


use Gica\Serialize\ObjectHydrator\ObjectUnserializer;
use Gica\Types\Guid;

class FromObjectID implements ObjectUnserializer
{

    /**
     * @inheritdoc
     */
    public function tryToUnserializeValue(string $objectClass, $serializedValue)
    {
        return Guid::fromString((string)$serializedValue);
    }
}