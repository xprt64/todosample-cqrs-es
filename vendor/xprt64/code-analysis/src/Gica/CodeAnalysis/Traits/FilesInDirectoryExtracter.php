<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\CodeAnalysis\Traits;


trait FilesInDirectoryExtracter
{
    protected function getFilesInDirectory($directory)
    {
        $result = [];

        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory));

        $it->rewind();
        while ($it->valid()) {

            $filePath = $it->key();

            $result[] = $filePath;

            $it->next();
        }

        return $result;

    }
}