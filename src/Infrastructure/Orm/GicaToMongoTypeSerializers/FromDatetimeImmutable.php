<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Orm\GicaToMongoTypeSerializers;


use Gica\Serialize\ObjectSerializer\Exception\ValueNotSerializable;
use Gica\Serialize\ObjectSerializer\Serializer;
use MongoDB\BSON\UTCDateTime;

class FromDatetimeImmutable implements Serializer
{
    public function serialize($value)
    {
        if (!$value instanceof \DateTimeImmutable) {
            throw new ValueNotSerializable();
        }

        return new UTCDateTime($value->getTimestamp() * 1000);
    }
}