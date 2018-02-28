<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace tests\unit\Gica\Serialize\ObjectHydratorWithNestedArrayTest;


use Gica\Serialize\MongoObjectHydratorAdapters\MongoLocalAdapterLocator;
use Gica\Serialize\ObjectHydrator\ObjectHydrator;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer\CompositeObjectUnserializer;

class ObjectHydratorWithNestedArrayTest extends \PHPUnit_Framework_TestCase
{

    public function test_hydrateObject()
    {
        $document = [
            'someArray'      => [
                [
                    'someNestedVar' => 123,
                ],
                [
                    'someNestedVar' => 234,
                ],
                [
                    'someNestedVar' => 345,
                ],
            ],
         ];

        $sut = new ObjectHydrator(new CompositeObjectUnserializer([]));

        /** @var MyObject $reconstructed */
        $reconstructed = $sut->hydrateObject(MyObject::class, $document);

        $this->assertInstanceOf(MyObject::class, $reconstructed);
        $this->assertInternalType('array', $reconstructed->getSomeArray());
        $this->assertSame($document['someArray'][0]['someNestedVar'], $reconstructed->getSomeArray()[0]->getSomeNestedVar());
        $this->assertSame($document['someArray'][1]['someNestedVar'], $reconstructed->getSomeArray()[1]->getSomeNestedVar());
        $this->assertSame($document['someArray'][2]['someNestedVar'], $reconstructed->getSomeArray()[2]->getSomeNestedVar());
    }
}

class MyObject
{

    /**
     * @var MyNestedObject[]
     */
    private $someArray;

    public function __construct(
        array $someArray = null
    )
    {
        $this->someArray = $someArray;
    }

    /**
     * @return MyNestedObject[]
     */
    public function getSomeArray()
    {
        return $this->someArray;
    }
}

class MyNestedObject
{
    private $someNestedVar;

    public function __construct(
        $someNestedVar
    )
    {
        $this->someNestedVar = $someNestedVar;
    }

    public function getSomeNestedVar()
    {
        return $this->someNestedVar;
    }
}
