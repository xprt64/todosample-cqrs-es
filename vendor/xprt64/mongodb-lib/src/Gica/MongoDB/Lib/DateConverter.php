<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\MongoDB\Lib;


use Gica\ValueObject\DateTimeImmutable\NullDateTimeImmutable;
use MongoDB\BSON\UTCDateTime;

class DateConverter
{
    public static function UTCDateTime(\DateTimeInterface $dateTime)
    {
        return new UTCDateTime($dateTime->getTimestamp() * 1000);
    }

    public static function toDateTimeImmutable(UTCDateTime $mongoDate = null):\DateTimeImmutable
    {
        if (!$mongoDate) {
            return new NullDateTimeImmutable();
        }

        return \DateTimeImmutable::createFromMutable($mongoDate->toDateTime());
    }
}