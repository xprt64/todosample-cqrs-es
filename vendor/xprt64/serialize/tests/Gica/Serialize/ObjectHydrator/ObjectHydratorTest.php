<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace tests\unit\Gica\Serialize;


use Gica\Serialize\ObjectHydrator\ObjectHydrator;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer\CompositeObjectUnserializer;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer\DateTimeImmutableFromString;

class ObjectHydratorTest extends \PHPUnit_Framework_TestCase
{

    public function test_hydrateObject()
    {
        $dateTimeImmutable = new \DateTimeImmutable("2017-01-02");

        $document = [
            'nestedObject'          => [
                'someNestedVar' => 123,
            ],
            'someInt'               => 456,
            'someString'            => "some str",
            'dateTimeImmutable'     => "2017-01-02",
            'someVar'               => 0.01,
            'someBool'              => true,
            'someArray'             => [4, 6, 8],
            'someNull'              => 'not-null-value',
            'propertyWithDocError'  => 2,
            'propertyWithShortType' => 2,

            'someNonExistingProperty' => 123,
            'propertyWithUnknownArray' => [1,2,3],
        ];

        $sut = new ObjectHydrator(new CompositeObjectUnserializer([new DateTimeImmutableFromString()]));

        /** @var MyObject $reconstructed */
        $reconstructed = $sut->hydrateObject(MyObject::class, $document);

        $this->assertInstanceOf(MyObject::class, $reconstructed);
        $this->assertInstanceOf(MyNestedObject::class, $reconstructed->getNestedObject());
        $this->assertSame($document['nestedObject']['someNestedVar'], $reconstructed->getNestedObject()->getSomeNestedVar());
        $this->assertSame($document['someInt'], $reconstructed->getSomeInt());
        $this->assertSame($document['someString'], $reconstructed->getSomeString());
        $this->assertEquals($dateTimeImmutable, $reconstructed->getDateTimeImmutable());
        $this->assertSame($document['someVar'], $reconstructed->getSomeVar());
        $this->assertSame($document['someArray'], $reconstructed->getSomeArray());
        $this->assertSame($document['someBool'], $reconstructed->getSomeBool());
        $this->assertSame($document['someNull'], $reconstructed->getSomeNull());
        $this->assertSame($document['propertyWithDocError'], $reconstructed->propertyWithDocError);
        $this->assertSame($document['propertyWithUnknownArray'], $reconstructed->propertyWithUnknownArray);
    }

    public function test_hydrateObjectProperty()
    {
        $document = [
            'nestedObject' => [
                'someNestedVar' => 123,
            ],
            'someInt'      => 456,
            'someString'   => "some str",
            'someVar'      => 0.01,
            'someFloat'    => 0.02,
            'someBool'     => true,
            'someBoolean'  => false,
            'someArray'    => [4, 6, 8],
            'someNull'     => 'not-null-value',
            'someRealNull' => null,

        ];

        $sut = new ObjectHydrator(new CompositeObjectUnserializer([]));

        $this->assertSame($document['someInt'], $sut->hydrateObjectProperty(MyObject::class, 'someInt', $document['someInt']));
        $this->assertSame($document['someString'], $sut->hydrateObjectProperty(MyObject::class, 'someString', $document['someString']));
        //$this->assertSame($document['someVar'], $sut->hydrateObjectProperty(MyObject::class, 'someVar', $document['someVar']));
        $this->assertSame($document['someArray'], $sut->hydrateObjectProperty(MyObject::class, 'someArray', $document['someArray']));
        $this->assertSame($document['someBool'], $sut->hydrateObjectProperty(MyObject::class, 'someBool', $document['someBool']));
        $this->assertSame($document['someBoolean'], $sut->hydrateObjectProperty(MyObject::class, 'someBoolean', $document['someBoolean']));
        $this->assertSame($document['someNull'], $sut->hydrateObjectProperty(MyObject::class, 'someNull', $document['someNull']));
        $this->assertSame($document['someFloat'], $sut->hydrateObjectProperty(MyObject::class, 'someFloat', $document['someFloat']));
        $this->assertNull($sut->hydrateObjectProperty(MyObject::class, 'someNull', null));
        $this->assertNull($sut->hydrateObject('null', 'someRealNull'));
    }
}

class MyObject
{
    /**
     * @var MyNestedObject
     */
    private $nestedObject;
    /**
     * @var int
     */
    private $someInt;
    /** @var float */
    private $someFloat;
    /**
     * @var string
     */
    private $someString;
    private $someVar;
    /**
     * @var \DateTimeImmutable
     */
    private $dateTimeImmutable;

    /**
     * @var int[]
     */
    private $someArray;
    /**
     * @var bool
     */
    private $someBool;
    /**
     * @var boolean
     */
    private $someBoolean;
    /**
     * @var null
     */
    private $someNull;

    /** @var  [] */
    public $propertyWithDocError;

    /** @var  a */
    public $propertyWithShortType;

    /** @var  array */
    public $propertyWithUnknownArray;

    public function __construct(
        ?MyNestedObject $nestedObject,
        ?int $someInt,
        ?string $someString,
        ?\DateTimeImmutable $dateTimeImmutable,
        $someVar = null,
        array $someArray = null,
        bool $someBool = false,
        $someNull = null
    )
    {
        $this->nestedObject = $nestedObject;
        $this->someInt = $someInt;
        $this->someString = $someString;
        $this->someVar = $someVar;
        $this->dateTimeImmutable = $dateTimeImmutable;
        $this->someArray = $someArray;
        $this->someBool = $someBool;
        $this->someNull = $someNull;
    }

    /**
     * @return MyNestedObject
     */
    public function getNestedObject(): MyNestedObject
    {
        return $this->nestedObject;
    }

    /**
     * @return int
     */
    public function getSomeInt(): int
    {
        return $this->someInt;
    }

    /**
     * @return string
     */
    public function getSomeString(): string
    {
        return $this->someString;
    }

    /**
     * @return mixed
     */
    public function getSomeVar()
    {
        return $this->someVar;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDateTimeImmutable(): \DateTimeImmutable
    {
        return $this->dateTimeImmutable;
    }

    public function getSomeArray()
    {
        return $this->someArray;
    }

    public function getSomeBool(): bool
    {
        return $this->someBool;
    }

    /**
     * @return null
     */
    public function getSomeNull()
    {
        return $this->someNull;
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


class a
{
}