<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Bin;


use Psr\Log\LoggerInterface;

class Logger implements LoggerInterface
{

    public function emergency($message, array $context = [])
    {
        $this->panic($message);
    }

    private function panic($message)
    {
        die($message . "\n");
    }

    private function show($message)
    {
        echo($message . "\n");
    }

    public function alert($message, array $context = [])
    {
        $this->panic($message);
    }

    public function critical($message, array $context = [])
    {
        $this->panic($message);
    }

    public function error($message, array $context = [])
    {
        $this->panic($message);
    }

    public function warning($message, array $context = [])
    {
        $this->show('WARNING:' . $message);
    }

    public function notice($message, array $context = [])
    {
        $this->show($message);
    }

    public function info($message, array $context = [])
    {
        $this->show($message);
    }

    public function debug($message, array $context = [])
    {
        $this->show('DEBUG:' . $message);
    }

    public function log($level, $message, array $context = [])
    {
        $this->show('LOG:' . $message);
    }
}