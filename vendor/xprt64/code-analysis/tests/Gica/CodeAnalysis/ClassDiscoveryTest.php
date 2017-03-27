<?php


namespace tests\Gica\CodeAnalysis;


use Gica\CodeAnalysis\ClassDiscovery;
use Gica\CodeAnalysis\MethodListenerDiscovery\ListenerClassValidator;
use Gica\CodeAnalysis\Shared\ClassSorter\AlphabeticalClassSorter;


class ClassDiscoveryTest extends \PHPUnit_Framework_TestCase
{

    public function test()
    {
        $sut = new ClassDiscovery(
            new WithoutNotAccepted(),
            new AlphabeticalClassSorter()
        );

        $sut->discover(__DIR__ . '/ClassDiscovery');

        $this->assertCount(3, $sut->getDiscoveredClasses());
    }
}

class WithoutNotAccepted implements ListenerClassValidator
{

    public function isClassAccepted(\ReflectionClass $typeHintedClass): bool
    {
        return false === stripos($typeHintedClass->getName(), 'NotAccepted');
    }
}
