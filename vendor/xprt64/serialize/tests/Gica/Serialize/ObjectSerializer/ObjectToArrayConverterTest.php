<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace tests\unit\Gica\Serialize\ObjectSerializer;


use Gica\Serialize\GicaToMongoTypeSerializers\FromEnum;
use Gica\Serialize\GicaToMongoTypeSerializers\FromGuid;
use Gica\Serialize\GicaToMongoTypeSerializers\FromSet;
use Gica\Serialize\ObjectSerializer\CompositeSerializer;
use Gica\Serialize\ObjectSerializer\ObjectSerializer;


class ObjectToArrayConverterTest extends \PHPUnit_Framework_TestCase
{
    /** @var ObjectSerializer */
    private $sut;

    protected function setUp()
    {
        $this->sut = new ObjectSerializer(
            new CompositeSerializer([
            ])
        );
    }

    public function test_string_array()
    {
        $converted = $this->sut->convert(['test1', 'test2']);

        $this->assertInternalType('array', $converted);

        $this->assertInternalType('string', $converted[0]);
        $this->assertSame('test1', $converted[0]);

        $this->assertInternalType('string', $converted[1]);
        $this->assertSame('test2', $converted[1]);
    }

    public function test_object()
    {
        $object = new MyObject('aaa', 'bbb');

        $converted = $this->sut->convert($object);

        $this->assertArraySubset(['a' => 'aaa', 'b' => 'bbb'], $converted);
    }

    public function test_MyObjectWithArrays()
    {
        $object = new MyObjectWithArrays([1, 2]);

        $converted = $this->sut->convert($object);

        $this->assertArraySubset(['arr' => [1, 2,]], $converted);
    }

    public function test_MyObjectWithNestedObject()
    {
        $object = new MyObjectWithNestedObject(new MyNestedObject('aaa'));

        $converted = $this->sut->convert($object);

        $this->assertArraySubset(['myNestedObject' => ['variable' => 'aaa']], $converted);
    }
}

class MyObject
{
    private $a;
    private $b;

    public function __construct($a, $b)
    {
        $this->a = $a;
        $this->b = $b;
    }
}

class MyObjectWithArrays
{
    private $arr;

    public function __construct($arr)
    {
        $this->arr = $arr;
    }
}

class MyObjectWithNestedObject
{

    /**
     * @var MyNestedObject
     */
    private $myNestedObject;

    public function __construct(MyNestedObject $myNestedObject)
    {
        $this->myNestedObject = $myNestedObject;
    }
}

class MyNestedObject
{

    private $variable;

    public function __construct($variable)
    {
        $this->variable = $variable;
    }
}