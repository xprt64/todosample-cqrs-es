<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib\Callback;


class IdentityFactory
{
    public static function generateIdentity()
    {
        return function ($x) {
            return $x;
        };
    }
}