<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib;


class DateFormatter
{
    public static function formatYMDHIS($timestamp = null)
    {
        if( 0 == func_num_args())
            $timestamp = time();

        return \date('Y-m-d H:i:s', $timestamp);
    }
}