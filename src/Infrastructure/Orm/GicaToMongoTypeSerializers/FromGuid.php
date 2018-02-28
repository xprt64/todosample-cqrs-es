<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Orm\GicaToMongoTypeSerializers;


use Gica\Serialize\ObjectSerializer\Exception\ValueNotSerializable;
use Gica\Serialize\ObjectSerializer\Serializer;
use Gica\Types\Guid;
use MongoDB\BSON\ObjectID;

class FromGuid implements Serializer
{
    public function serialize($value)
    {
        if (!$value instanceof Guid) {
            throw new ValueNotSerializable();
        }

        return new ObjectID((string)$value);
    }
}