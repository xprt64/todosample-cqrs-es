<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Orm\MongoObjectHydratorAdapters;


use Gica\Serialize\ObjectHydrator\AdapterLocator\LocalAdapterLocator;

class MongoLocalAdapterLocator extends LocalAdapterLocator
{
    protected function getNamespace(): string
    {
        return __NAMESPACE__ . '\\Adapters';
    }
}