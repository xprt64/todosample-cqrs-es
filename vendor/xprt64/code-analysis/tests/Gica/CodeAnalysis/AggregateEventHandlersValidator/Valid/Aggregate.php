<?php


namespace tests\Gica\CodeAnalysis\AggregateEventHandlersValidator\Valid;


use tests\Gica\CodeAnalysis\Events\Event1;

class Aggregate
{
    public function applyEvent1(Event1 $event)
    {

    }

    public function someOtherMethod()
    {
    }
}