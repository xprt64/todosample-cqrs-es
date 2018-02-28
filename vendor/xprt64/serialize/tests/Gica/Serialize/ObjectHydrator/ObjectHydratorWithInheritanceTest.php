<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace tests\unit\Gica\Serialize\ObjectHydratorWithInheritanceTest;


use Gica\Serialize\MongoObjectHydratorAdapters\MongoLocalAdapterLocator;
use Gica\Serialize\ObjectHydrator\ObjectHydrator;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer\CompositeObjectUnserializer;

class ObjectHydratorWithInheritanceTest extends \PHPUnit_Framework_TestCase
{

    public function test_hydrateObject()
    {
        $document = [
            'someValue' => 456,
        ];

        $sut = new ObjectHydrator(new CompositeObjectUnserializer([]));

        /** @var MyObject $reconstructed */
        $reconstructed = $sut->hydrateObject(MyObject::class, $document);

        $this->assertInstanceOf(MyObject::class, $reconstructed);
        $this->assertSame($document['someValue'], $reconstructed->getSomeValue());
    }
}

class MyObject extends MyParentClass
{

}

class MyParentClass
{
    /** @var int */
    private $someValue;

    public function __construct($someValue)
    {
        $this->someValue = $someValue;
    }

    public function getSomeValue(): int
    {
        return $this->someValue;
    }
}