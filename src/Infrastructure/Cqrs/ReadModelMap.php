<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Cqrs;

/**
 * --- generated by /work/github/xprt64/todo-cqrs-es/vendor/xprt64/cqrs-es/src/Gica/Cqrs/CodeGeneration/CodeGenerator.php at 2017-03-27T21:11:52+03:00 ---
 */
class ReadModelMap
{
    public function getEventHandlersDefinitions():array
    {
        return [
            \Domain\Read\Todo\TodoList::class => [
                [\Domain\Write\Todo\TodoAggregate\Event\ANewTodoWasAdded::class, 'onANewTodoWasAdded'],
                [\Domain\Write\Todo\TodoAggregate\Event\ATodoWasMarkedAsDone::class, 'onATodoWasMarkedAsDone'],
                [\Domain\Write\Todo\TodoAggregate\Event\ATodoWasUnmarkedAsDone::class, 'onATodoWasUnmarkedAsDone'],
                [\Domain\Write\Todo\TodoAggregate\Event\ATodoWasDeleted::class, 'onATodoWasDeleted'],
            ],
        ];
    }
}