<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Gica\Selector;


interface Filter
{
    /**
     * @immutable
     * @param array $fields
     * @return array the new fields
     */
    public function applyFilter(array $fields):array;
}