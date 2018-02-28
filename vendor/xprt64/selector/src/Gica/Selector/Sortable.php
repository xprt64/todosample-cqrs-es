<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Gica\Selector;


interface Sortable
{
    /**
     * @immutable
     * @param $field
     * @param bool $ascending
     * @return static
     */
    public function sort($field, bool $ascending);
}