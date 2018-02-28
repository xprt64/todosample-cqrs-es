<?php

namespace tests\Gica\Serialize\ObjectSerializer;

use Gica\Serialize\ObjectSerializer\ArraySerializer;

class ArraySerializerTest extends \PHPUnit_Framework_TestCase
{

    public function test()
    {
        $serializer = $this->getMockBuilder(\Gica\Serialize\ObjectSerializer\ObjectSerializer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $serializer->expects($this->exactly(3))
            ->method('convert')
            ->willReturnCallback(function ($arg) {
                return $arg + 1;
            });

        $sut = new ArraySerializer($serializer);

        $result = $sut->convertArray([1, 2, 3]);

        $this->assertEquals([2, 3, 4], $result);
    }
}
