<?php


namespace tests\Gica\CodeAnalysis;


use Gica\CodeAnalysis\MethodListenerDiscovery;
use Gica\CodeAnalysis\MethodListenerDiscovery\ListenerClassValidator;
use Gica\CodeAnalysis\MethodListenerDiscovery\ListenerMethod;
use Gica\CodeAnalysis\MethodListenerDiscovery\MapGrouper\GrouperByEvent;
use Gica\CodeAnalysis\MethodListenerDiscovery\MessageClassDetector;
use Gica\CodeAnalysis\Shared\ClassSorter\AlphabeticalClassSorter;
use tests\Gica\CodeAnalysis\MethodListenerDiscoveryData\Message;
use tests\Gica\CodeAnalysis\MethodListenerDiscoveryData\MyMessage;
use tests\Gica\CodeAnalysis\MethodListenerDiscoveryData\SomeValidListener;
use tests\Gica\CodeAnalysis\MethodListenerDiscoveryData\SomeValidListenerWithNoPsr4\MyMessage as MyMessageNoPsr4;


class MethodListenerDiscoveryTest extends \PHPUnit_Framework_TestCase
{

    public function test()
    {
        $sut = new MethodListenerDiscovery(
            new MyMessageClassDetector(),
            new MyClassValidatorForMethodListener(),
            new AlphabeticalClassSorter()
        );

        $map = $sut->discoverListeners(__DIR__ . '/MethodListenerDiscoveryData');

        $this->assertCount(2, $map);

        $map = (new GrouperByEvent)->groupMap($map);

        $this->assertArrayHasKey(MyMessage::class, $map);

        $this->assertCount(1, $map[MyMessage::class]);

        /** @var ListenerMethod $listener */
        $listener = $map[MyMessage::class][0];
        $this->assertEquals('xxxSomeMethod', $listener->getMethodName());
        $this->assertEquals(SomeValidListener::class, $listener->getClassName());

        $this->assertArrayHasKey(MyMessageNoPsr4::class, $map);

        $this->assertCount(1, $map[MyMessageNoPsr4::class]);

        /** @var ListenerMethod $listener */
        $listener = $map[MyMessageNoPsr4::class][0];
        $this->assertEquals('xxxSomeMethodWithNoPsr4', $listener->getMethodName());
    }
}

class MyClassValidatorForMethodListener implements ListenerClassValidator
{

    public function isClassAccepted(\ReflectionClass $typeHintedClass): bool
    {
        return false === stripos($typeHintedClass->getName(), 'NotAccepted');
    }
}

class MyMessageClassDetector implements MessageClassDetector
{

    public function isMessageClass(\ReflectionClass $typeHintedClass): bool
    {
        return $typeHintedClass->implementsInterface(Message::class);
    }

    public function isMethodAccepted(\ReflectionMethod $reflectionMethod): bool
    {
        return 0 === stripos($reflectionMethod->getName(), 'xxx');
    }
}

