<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Gica\Selector;


interface Skippable
{
    /**
     * @immutable
     * @param int $offset
     * @return static
     */
    public function skip(int $offset);
}