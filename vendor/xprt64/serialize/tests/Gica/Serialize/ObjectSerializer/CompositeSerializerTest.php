<?php

namespace tests\Gica\Serialize\ObjectSerializer;

use Gica\Serialize\ObjectSerializer\CompositeSerializer;
use Gica\Serialize\ObjectSerializer\Exception\ValueNotSerializable;

class CompositeSerializerTest extends \PHPUnit_Framework_TestCase
{

    public function test()
    {
        $s1 = $this->getMockBuilder(\Gica\Serialize\ObjectSerializer\Serializer::class)->getMock();

        $s1->expects($this->once())
            ->method('serialize')
            ->with('abc')
            ->willReturn('def');

        $s2 = $this->getMockBuilder(\Gica\Serialize\ObjectSerializer\Serializer::class)->getMock();

        $s2->expects($this->never())
            ->method('serialize');

        $sut = new CompositeSerializer([$s1, $s2]);

        $sut->serialize('abc');
    }

    public function test_ValueNotSerializable()
    {
        $s1 = $this->getMockBuilder(\Gica\Serialize\ObjectSerializer\Serializer::class)->getMock();

        $s1->expects($this->once())
            ->method('serialize')
            ->with('abc')
            ->willThrowException(new ValueNotSerializable());

        $s2 = $this->getMockBuilder(\Gica\Serialize\ObjectSerializer\Serializer::class)->getMock();


        $s2->expects($this->once())
            ->method('serialize')
            ->with('abc')
            ->willReturn('def');

        $sut = new CompositeSerializer([$s1, $s2]);

        $sut->serialize('abc');
    }
}
