<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Types;


use Gica\Types\DateTimeImmutable\NullDateTimeImmutable;

class DateTimeImmutable extends \DateTimeImmutable
{
    public static function createFromFormat($format, $time, $timezone = null)
    {
        $result = parent::createFromFormat($format, $time, $timezone);

        if (false === $result)
            return new NullDateTimeImmutable();
        else
            return $result;
    }

    public static function deserialize($document)
    {
        if (!$document) {
            return new NullDateTimeImmutable();
        }

        $date = new self($document['date']);

        if (3 == $document['timezone_type']) {
            $date->setTimezone(new \DateTimeZone($document['timezone']));
        }

        return $date;
    }

    function __toString()
    {
        return $this->format('Y-m-d H:i:s');
    }

    public static function now()
    {
        return new static();
    }
}