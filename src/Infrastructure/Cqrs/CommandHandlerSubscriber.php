<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Cqrs;

use Gica\Cqrs\Command\CommandSubscriber\CommandSubscriberByMap;

/**
 * --- generated by /work/github/xprt64/todo-cqrs-es/vendor/xprt64/cqrs-es/src/Gica/Cqrs/CodeGeneration/CodeGenerator.php at 2017-03-27T21:11:52+03:00 ---
 */
class CommandHandlerSubscriber extends CommandSubscriberByMap
{
    protected function getCommandHandlersDefinitions():array
    {
        return [
            \Domain\Write\Todo\TodoAggregate\Command\AddNewTodo::class => [
                [\Domain\Write\Todo\TodoAggregate::class, 'handleAddNewTodo'],
            ],

            \Domain\Write\Todo\TodoAggregate\Command\MarkTodoAsDone::class => [
                [\Domain\Write\Todo\TodoAggregate::class, 'handleMarkTodoAsDone'],
            ],

            \Domain\Write\Todo\TodoAggregate\Command\UnmarkTodoAsDone::class => [
                [\Domain\Write\Todo\TodoAggregate::class, 'handleUnmarkTodoAsDone'],
            ],

            \Domain\Write\Todo\TodoAggregate\Command\DeleteTodo::class => [
                [\Domain\Write\Todo\TodoAggregate::class, 'handleDeleteTodo'],
            ],
        ];
    }
}