<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Orm\MongoObjectHydratorAdapters;


use Gica\Serialize\ObjectHydrator\ObjectHydrator;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer\CompositeObjectUnserializer;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer\WithStaticFactory\FromPrimitive;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer\WithStaticFactory\FromString;

class MongoObjectHydrator extends ObjectHydrator
{

    public function __construct()
    {
        parent::__construct(
            new CompositeObjectUnserializer(
                [
                    new FromPrimitive(),
                    new FromString(),
                    new MongoLocalAdapterLocator()
                ]
            )
        );
    }
}