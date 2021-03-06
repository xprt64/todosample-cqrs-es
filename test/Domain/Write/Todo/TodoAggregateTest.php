<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

use Domain\Write\Todo\TodoAggregate;
use Domain\Write\Todo\TodoAggregate\Command\AddNewTodo;
use Domain\Write\Todo\TodoAggregate\Event\ANewTodoWasAdded;
use Dudulina\Testing\BddAggregateTestHelper;


class TodoAggregateTest extends PHPUnit_Framework_TestCase
{

    public function test_handleAddNewTodo()
    {
        $command = new AddNewTodo(123, 'test');

        $expectedEvent = new ANewTodoWasAdded(123, 'test');

        $sut = new TodoAggregate();

        $helper = new BddAggregateTestHelper(
            new \CommandHandlersMap()
        );

        $helper->onAggregate($sut);
        $helper->given();
        $helper->when($command);
        $helper->then($expectedEvent);

        $this->assertTrue(true);//fake assertion
    }

    public function test_handleAddNewTodo_idempotent()
    {
        $command = new AddNewTodo(
            123, 'test'
        );

        $priorEvent = new ANewTodoWasAdded('test');

        $sut = new TodoAggregate();

        $helper = new BddAggregateTestHelper(
            new \CommandHandlersMap()
        );

        $helper->onAggregate($sut);
        $helper->given($priorEvent);
        $helper->when($command);
        $helper->then();//no events must be yielded

        $this->assertTrue(true);//fake assertion
    }
}
