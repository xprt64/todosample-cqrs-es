<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib;


class Url
{
    public static function makeAbsolute($url)
    {
        if (!preg_match('#^(http)|(ftp)|(https)#ims', $url)) {
            return 'http://' . $url;
        }
        return $url;
    }
}