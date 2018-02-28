<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Orm\MongoObjectHydratorAdapters\Adapters\DateTimeImmutable;


use Gica\Serialize\ObjectHydrator\ObjectUnserializer;
use MongoDB\BSON\UTCDateTime;

class FromUTCDateTime implements ObjectUnserializer
{
    public function tryToUnserializeValue(string $objectClass, $serializedValue)
    {
        /** @var UTCDateTime $serializedValue */
        return \DateTimeImmutable::createFromMutable($serializedValue->toDateTime())->setTimezone(new \DateTimeZone(date_default_timezone_get()));
    }
}