<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Orm\MongoObjectHydratorAdapters;


use Gica\Serialize\ObjectHydrator\ArrayHydrator;

class MongoArrayHydrator extends ArrayHydrator
{

    public function __construct(
        MongoObjectHydrator $objectHydrator
    )
    {
        parent::__construct($objectHydrator);
    }
}