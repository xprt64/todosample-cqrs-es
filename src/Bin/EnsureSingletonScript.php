<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Bin;


use Psr\Log\LoggerInterface;

class EnsureSingletonScript
{

    static $locks = [];

    public static function lock($name, ?LoggerInterface $logger = null)
    {
        $lock = new LockFile($name);

        self::$locks[] = $lock;

        if (!$lock->tryLock()) {
            if ($logger) {
                $logger->info("nu pot bloca (este deja blocat?)\n");
            }
            die();
        }

        return true;
    }
}