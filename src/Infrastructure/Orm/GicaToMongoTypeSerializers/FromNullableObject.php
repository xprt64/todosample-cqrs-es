<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Orm\GicaToMongoTypeSerializers;


use Crm\Write\Autentificare\UserAggregate\ValueObject\IdUser;
use Gica\Serialize\ObjectSerializer\Exception\ValueNotSerializable;
use Gica\Serialize\ObjectSerializer\Serializer;
use Gica\Types\Guid;
use MongoDB\BSON\ObjectID;

class FromNullableObject implements Serializer
{
    public function serialize($value)
    {
        $isNullMethod = [$value, 'isNull'];

        if (is_callable($isNullMethod) && true === call_user_func($isNullMethod)) {
            return null;
        }

        throw new ValueNotSerializable();
    }
}