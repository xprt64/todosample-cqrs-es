<?php


namespace tests\Gica\CodeAnalysis\MapCodeGenerator;


use Gica\CodeAnalysis\MethodListenerDiscovery\ListenerMethod;
use Gica\CodeAnalysis\MethodListenerDiscovery\MapCodeGenerator\GroupedByEventMapCodeGenerator;


class GroupedByEventMapCodeGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $sut = new GroupedByEventMapCodeGenerator();

        $result = $sut->generateAndGetFileContents([
            new ListenerMethod(
                new \ReflectionClass(SomeClass::class),
                'someMethod',
                SomeEvent::class
            ),
            new ListenerMethod(
                new \ReflectionClass(SomeClass::class),
                'someOtherMethod',
                SomeOtherEvent::class
            ),
        ], 'return [/*do not modify this line!*/];');

        $this->assertStringStartsWith('return ', $result);
        $this->assertStringEndsWith(';', $result);

        $evaluated = eval($result);

        $this->assertCount(2, $evaluated);

        $this->assertArrayHasKey(SomeEvent::class, $evaluated);
        $this->assertArrayHasKey(SomeOtherEvent::class, $evaluated);
    }
}

class SomeEvent
{

}

class SomeOtherEvent
{

}

class SomeClass
{
    public function someMethod()
    {

    }

    public function someOtherMethod()
    {

    }
}
