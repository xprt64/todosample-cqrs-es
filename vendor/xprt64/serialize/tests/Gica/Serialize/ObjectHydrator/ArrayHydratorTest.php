<?php

namespace tests\Gica\Serialize\ObjectHydrator;

use Gica\Serialize\ObjectHydrator\ArrayHydrator;

class ArrayHydratorTest extends \PHPUnit_Framework_TestCase
{

    public function test()
    {
        $objectHydrator = $this->getMockBuilder(\Gica\Serialize\ObjectHydrator\ObjectHydrator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $objectHydrator
            ->expects($this->exactly(3))
            ->method('hydrateObject')
            ->willReturnCallback(function($class, $arg){
                return $arg+1;
            });

        $sut = new ArrayHydrator($objectHydrator);

        $result = $sut->hydrateArray('int', [1,2,3]);

        $this->assertEquals([2,3,4], $result);

        $this->assertSame($objectHydrator, $sut->getObjectHydrator());
    }
}
