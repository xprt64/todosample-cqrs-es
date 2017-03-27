<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib;


class FilesDiscovery
{
    public function getFullFilenamesInDirectory($dir, $recursive = false)
    {
        if (substr($dir, -1) != DIRECTORY_SEPARATOR)
            $dir .= DIRECTORY_SEPARATOR;

        $files = array ();

        if (is_dir($dir))
        {
            $dh = opendir($dir);
            if (false === $dh)
            {
                trigger_error("opendir $dir failed", E_USER_ERROR);
                return array ();
            }

            while (false !== ($filename = readdir($dh)))
                if ($filename != "." && $filename != "..")
                {
                    $path = $dir . $filename;

                    if (is_dir($path))
                    {
                        if ($recursive)
                            $files += (array) $this->getFullFilenamesInDirectory($path, true);
                    }
                    else
                        $files[] = $path;
                }
            closedir($dh);
        }

        return $files;
    }
}