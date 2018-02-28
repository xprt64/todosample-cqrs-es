<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Gica\Selector;


interface Limitable
{
    /**
     * @immutable
     * @param int $items
     * @return static
     */
    public function limit(int $items);
}