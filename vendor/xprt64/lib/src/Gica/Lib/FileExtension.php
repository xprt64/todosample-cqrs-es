<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib;


class FileExtension
{
    public function getFileExtension($fileName)
    {
        return array_reverse(explode('.', $fileName))[0];
    }
}