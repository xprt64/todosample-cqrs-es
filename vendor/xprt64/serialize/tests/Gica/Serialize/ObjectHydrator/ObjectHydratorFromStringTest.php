<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace tests\unit\Gica\Serialize\ObjectHydratorFromStringTest;


use Gica\Serialize\MongoObjectHydratorAdapters\MongoLocalAdapterLocator;
use Gica\Serialize\ObjectHydrator\ObjectHydrator;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer\CompositeObjectUnserializer;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer\WithStaticFactory\FromPrimitive;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer\WithStaticFactory\FromString;

class ObjectHydratorFromStringTest extends \PHPUnit_Framework_TestCase
{

    public function test_hydrateObject()
    {
        $document = new Stringable(123);

        $sut = new ObjectHydrator(
            new CompositeObjectUnserializer(
                [
                    new FromPrimitive(),
                    new FromString()
                ]
            )
        );

        /** @var MyObject $reconstructed */
        $reconstructed = $sut->hydrateObject(MyObject::class, $document);

        $this->assertInstanceOf(MyObject::class, $reconstructed);
        $this->assertSame((string)$document, $reconstructed->getValue());
        $this->assertSame('123', $reconstructed->getValue());
    }
}

class MyObject
{
    private $value;

    public function getValue()
    {
        return $this->value;
    }

    public static function fromString(string $str)
    {
        $other = new self;

        $other->value = $str;

        return $other;
    }
}


class Stringable
{

    private $value;

    public function __construct(
        $value
    )
    {
        $this->value = $value;
    }

    function __toString()
    {
        return (string)$this->value;
    }
}