<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib;


class Cli
{
    function parseArgv($argv = [])
    {
        $ret = [];

        if (!$argv)
            return $ret;

        foreach ($argv as $v) {
            if (preg_match('#^(?P<nume>.+?)=(?P<valoare>.*)$#i', $v, $matches))
                $ret[$matches['nume']] = $matches['valoare'];
        }

        return $ret;
    }
}