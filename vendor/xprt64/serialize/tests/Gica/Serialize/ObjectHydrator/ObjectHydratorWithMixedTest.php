<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace tests\unit\Gica\Serialize\ObjectHydratorWithMixedTest;


use Gica\Serialize\ObjectHydrator\ObjectHydrator;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer\CompositeObjectUnserializer;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer\DateTimeImmutableFromString;

class ObjectHydratorWithMixedTest extends \PHPUnit_Framework_TestCase
{

    public function test_hydrateObject()
    {
        $document = [
            'someMixed'            => "some mixed",
        ];

        $sut = new ObjectHydrator(new CompositeObjectUnserializer([new DateTimeImmutableFromString()]));

        /** @var MyObject $reconstructed */
        $reconstructed = $sut->hydrateObject(MyObject::class, $document);

        $this->assertInstanceOf(MyObject::class, $reconstructed);
        $this->assertSame($document['someMixed'], $reconstructed->getSomeMixed());
    }

    public function test_hydrateObjectProperty()
    {
        $document = [
            'someMixed'            => "some mixed",
        ];

        $sut = new ObjectHydrator(new CompositeObjectUnserializer([]));

        $this->assertSame($document['someMixed'], $sut->hydrateObjectProperty(MyObject::class, 'someMixed', $document['someMixed']));
    }
}

class MyObject
{
    /**
     * @var mixed
     */
    private $someMixed;

    public function __construct($someMixed)
    {
        $this->someMixed = $someMixed;
    }

    /**
     * @return mixed
     */
    public function getSomeMixed()
    {
        return $this->someMixed;
    }

}


class a
{
}