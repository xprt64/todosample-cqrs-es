<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace tests\unit\Gica\Serialize\ObjectHydratorWithInterfaceTest;


use Gica\Serialize\MongoObjectHydratorAdapters\MongoLocalAdapterLocator;
use Gica\Serialize\ObjectHydrator\ObjectHydrator;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer\CompositeObjectUnserializer;

class ObjectHydratorWithInterfaceTest extends \PHPUnit_Framework_TestCase
{

    public function test_hydrateObject()
    {
        $document = [
            'someInterface' => [
                'someValue' => 456,
            ],
            '@classes'      => [
                'someInterface' => MyConcreteObject::class,
            ],
        ];

        $sut = new ObjectHydrator(new CompositeObjectUnserializer([]));

        /** @var MyObject $reconstructed */
        $reconstructed = $sut->hydrateObject(MyObject::class, $document);

        $this->assertInstanceOf(MyObject::class, $reconstructed);
        $this->assertInstanceOf(MyConcreteObject::class, $reconstructed->getSomeInterface());
        $this->assertSame($document['someInterface']['someValue'], $reconstructed->getSomeInterface()->getSomeValue());
    }
}

class MyObject
{
    /** @var MyInterface */
    private $someInterface;

    public function __construct(MyInterface $someInterface)
    {
        $this->someInterface = $someInterface;
    }

    public function getSomeInterface(): MyInterface
    {
        return $this->someInterface;
    }

}

interface MyInterface
{

    public function getSomeValue(): int;
}

class MyConcreteObject implements MyInterface
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