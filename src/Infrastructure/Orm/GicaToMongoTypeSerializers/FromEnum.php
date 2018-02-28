<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Orm\GicaToMongoTypeSerializers;


use Gica\Serialize\ObjectSerializer\Exception\ValueNotSerializable;
use Gica\Serialize\ObjectSerializer\Serializer;
use Gica\Types\Enum;
use MongoDB\BSON\ObjectID;

class FromEnum implements Serializer
{
    public function serialize($value)
    {
        if (!$value instanceof Enum) {
            throw new ValueNotSerializable();
        }

        return $value->toPrimitive();
    }
}