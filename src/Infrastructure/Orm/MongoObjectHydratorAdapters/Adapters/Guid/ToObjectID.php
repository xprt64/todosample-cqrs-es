<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Orm\MongoObjectHydratorAdapters\Adapters\Guid;


use Gica\Types\Guid;

class ToObjectID
{

    /**
     * @inheritdoc
     */
    public function tryToUnserialize(string $objectClass, $serializedValue)
    {
        return Guid::fromString((string)$serializedValue);
    }
}