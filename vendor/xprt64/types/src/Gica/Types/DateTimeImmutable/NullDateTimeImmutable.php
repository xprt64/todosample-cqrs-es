<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Types\DateTimeImmutable;


use Gica\Types\DateTimeImmutable;

class NullDateTimeImmutable extends DateTimeImmutable
{

    public function __construct()
    {
    }

    public function format($format)
    {
        return '';
    }

}