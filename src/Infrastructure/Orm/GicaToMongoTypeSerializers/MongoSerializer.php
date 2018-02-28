<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Orm\GicaToMongoTypeSerializers;


use Gica\Serialize\ObjectSerializer\CompositeSerializer;
use Gica\Serialize\ObjectSerializer\ObjectSerializer;


class MongoSerializer extends ObjectSerializer
{

    public function __construct()
    {
        parent::__construct(new CompositeSerializer([
            new FromNullableObject(),
            new FromEnum(),
            new FromGuid(),
            new FromSet(),
            new FromDatetimeImmutable(),
         ]));
    }
}