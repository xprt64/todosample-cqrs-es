<?php

namespace tests\Gica\Serialize\ObjectHydrator\ObjectUnserializer;

use Gica\Serialize\ObjectHydrator\Exception\AdapterNotFoundException;
use Gica\Serialize\ObjectHydrator\Exception\ValueNotUnserializable;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer\CompositeObjectUnserializer;

class CompositeObjectUnserializerTest extends \PHPUnit_Framework_TestCase
{

    public function test()
    {
        $u1 = $this->getMockBuilder(ObjectUnserializer::class)->getMock();

        $u1->expects($this->once())
            ->method('tryToUnserializeValue')
            ->willThrowException(new AdapterNotFoundException());

        $u2 = $this->getMockBuilder(ObjectUnserializer::class)->getMock();

        $u2->expects($this->once())
            ->method('tryToUnserializeValue')
            ->willThrowException(new ValueNotUnserializable());

        $u3 = $this->getMockBuilder(ObjectUnserializer::class)->getMock();

        $u3->expects($this->once())
            ->method('tryToUnserializeValue')
            ->willReturnCallback(function ($class, $value) {
                return $value + 1;
            });

        $sut = new CompositeObjectUnserializer([$u1, $u2, $u3]);

        $result = $sut->tryToUnserializeValue('aClass', 2);

        $this->assertEquals(2 + 1, $result);
    }
}
