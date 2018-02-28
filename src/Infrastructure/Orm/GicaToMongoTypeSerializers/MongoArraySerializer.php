<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Orm\GicaToMongoTypeSerializers;


use Gica\Serialize\ObjectSerializer\ArraySerializer;


class MongoArraySerializer extends ArraySerializer
{
    public function __construct(
        MongoSerializer $serializer
    )
    {
        parent::__construct($serializer);
    }
}