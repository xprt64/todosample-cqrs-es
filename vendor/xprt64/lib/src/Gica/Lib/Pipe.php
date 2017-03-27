<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib;


class Pipe
{
    protected $start;

    protected $middlewares = [];

    public function __construct($start)
    {
        $this->start = $start;
    }

    public function pipe(callable $middleware)
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    public function end()
    {
        $current = $this->start;

        foreach($this->middlewares as $middleware)
        {
            $current = $middleware($current);
        }

        return $current;
    }
}