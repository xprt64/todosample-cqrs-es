<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Gica\Selector;


interface Filtrable
{
    /**
     * @immutable
     * @param Filter $filter
     * @param string|null $filterId
     * @return static
     */
    public function addFilter(Filter $filter, string $filterId = null);

    /**
     * @immutable
     * @param string $filterId
     * @return static
     */
    public function removeFilterById(string $filterId);
}