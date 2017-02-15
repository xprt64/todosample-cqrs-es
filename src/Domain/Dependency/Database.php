<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Domain\Dependency;


use MongoDB\Collection;

interface Database
{
    public function selectCollection($collectionName):Collection;
}