<?php


namespace tests\Gica\CodeAnalysis;


use Gica\CodeAnalysis\AggregateEventHandlersValidator;
use Gica\CodeAnalysis\MethodListenerDiscovery\ListenerClassValidator;
use Gica\CodeAnalysis\MethodListenerDiscovery\ListenerClassValidator\AnyPhpClassIsAccepted;


class AggregateEventHandlersValidatorTest extends \PHPUnit_Framework_TestCase
{

    public function test()
    {
        $sut = new AggregateEventHandlersValidator(
            new OnlyAggregate()
        );

        $sut->validateEventHandlers(__DIR__ . '/AggregateEventHandlersValidator/Valid');
    }

    public function testWithOtherNonAcceptedFiles()
    {
        $sut = new AggregateEventHandlersValidator(
            new AnyPhpClassIsAccepted()
        );

        $sut->validateEventHandlers(__DIR__ . '/AggregateEventHandlersValidator/WithOtherFiles');
    }

    public function testWithInvalidAggregate()
    {
        $sut = new AggregateEventHandlersValidator(
            new AnyPhpClassIsAccepted()
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageRegExp('#Method\'s name is invalid#ims');

        $sut->validateEventHandlers(__DIR__ . '/AggregateEventHandlersValidator/Invalid');
    }

    public function testWithInvalidAggregateWithNoTypeHinted()
    {
        $sut = new AggregateEventHandlersValidator(
            new AnyPhpClassIsAccepted()
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageRegExp('#Method parameter is not type hinted#ims');

        $sut->validateEventHandlers(__DIR__ . '/AggregateEventHandlersValidator/InvalidWithNoTypeHinted');
    }
}


class OnlyAggregate implements ListenerClassValidator
{

    public function isClassAccepted(\ReflectionClass $typeHintedClass): bool
    {
        return $typeHintedClass->getName() === 'Aggregate';
    }
}