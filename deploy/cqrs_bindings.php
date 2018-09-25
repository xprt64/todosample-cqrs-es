<?php 




class CommandHandlersMap
{
    private static $map = [
            \Domain\Write\Todo\TodoAggregate\Command\AddNewTodo::class => [
                [\Domain\Write\Todo\TodoAggregate::class, 'handleAddNewTodo'],
            ],

            \Domain\Write\Todo\TodoAggregate\Command\RenameTodo::class => [
                [\Domain\Write\Todo\TodoAggregate::class, 'handleRenameTodo'],
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

    public static function getMap(): array
    {
        return self::$map;
    }
}



class CommandValidatorSubscriber
{
    private static $map = [

        ];

    public static function getMap(): array
    {
        return self::$map;
    }
}



class EventListenersMap
{
    private static $map = [
            \Domain\Write\Todo\TodoAggregate\Event\ANewTodoWasAdded::class => [
                [\Domain\Read\Todo\TodoList::class, 'onANewTodoWasAdded'],
                [\Domain\Read\Todo\TodoDetails::class, 'onANewTodoWasAdded'],
            ],

            \Domain\Write\Todo\TodoAggregate\Event\ATodoWasMarkedAsDone::class => [
                [\Domain\Read\Todo\TodoDetails::class, 'onATodoWasMarkedAsDone'],
            ],

            \Domain\Write\Todo\TodoAggregate\Event\ANewTodoWasRenamed::class => [
                [\Domain\Read\Todo\TodoList::class, 'onANewTodoWasRenamed'],
                [\Domain\Read\Todo\TodoDetails::class, 'onANewTodoWasRenamed'],
            ],

            \Domain\Write\Todo\TodoAggregate\Event\ATodoWasUnmarkedAsDone::class => [
                [\Domain\Read\Todo\TodoDetails::class, 'onATodoWasUnmarkedAsDone'],
            ],

            \Domain\Write\Todo\TodoAggregate\Event\ATodoWasDeleted::class => [
                [\Domain\Read\Todo\TodoList::class, 'onATodoWasDeleted'],
                [\Domain\Read\Todo\TodoDetails::class, 'onATodoWasDeleted'],
            ],
        ];

    public static function getMap(): array
    {
        return static::$map;
    }
}


class QueryAskersMap
{
    private static $map = [
            \Domain\Query\Todo\WhatIsTheTitleOfTheTodo::class => [
                [\Domain\Read\Todo\TodoList::class, 'whenAnsweredWhatIsTheTitleOfTheTodo'],
            ],

            \Domain\Query\Todo\WhatIsTheStatusOfTheTodo::class => [
                [\Domain\Read\Todo\TodoList::class, 'whenAnsweredWhatIsTheStatusOfTheTodo'],
            ],
        ];

    public static function getMap(): array
    {
        return self::$map;
    }
}



class QueryHandlersMap
{
    private static $map = [
            \Domain\Query\Todo\WhatIsTheStatusOfTheTodo::class => [
                [\Domain\Read\Todo\TodoDetails::class, 'whatIsTheStatusOfTheTodo'],
            ],

            \Domain\Query\Todo\WhatIsTheTitleOfTheTodo::class => [
                [\Domain\Read\Todo\TodoDetails::class, 'whatIsTheTitleOfTheTodo'],
            ],
        ];

    public static function getMap(): array
    {
        return self::$map;
    }
}


class ReadModelsMap
{
    private static $map = [
            \Domain\Read\Todo\TodoDetails::class => [
                [\Domain\Write\Todo\TodoAggregate\Event\ANewTodoWasAdded::class, 'onANewTodoWasAdded'],
                [\Domain\Write\Todo\TodoAggregate\Event\ATodoWasMarkedAsDone::class, 'onATodoWasMarkedAsDone'],
                [\Domain\Write\Todo\TodoAggregate\Event\ANewTodoWasRenamed::class, 'onANewTodoWasRenamed'],
                [\Domain\Write\Todo\TodoAggregate\Event\ATodoWasUnmarkedAsDone::class, 'onATodoWasUnmarkedAsDone'],
                [\Domain\Write\Todo\TodoAggregate\Event\ATodoWasDeleted::class, 'onATodoWasDeleted'],
            ],

            \Domain\Read\Todo\TodoList::class => [
                [\Domain\Write\Todo\TodoAggregate\Event\ANewTodoWasAdded::class, 'onANewTodoWasAdded'],
                [\Domain\Write\Todo\TodoAggregate\Event\ANewTodoWasRenamed::class, 'onANewTodoWasRenamed'],
                [\Domain\Write\Todo\TodoAggregate\Event\ATodoWasDeleted::class, 'onATodoWasDeleted'],
            ],
        ];

    public static function getMap(): array
    {
        return self::$map;
    }
}



class SagaEventProcessorsMap
{
    private static $map = [
            \Domain\Write\Todo\TodoAggregate\Event\ANewTodoWasAdded::class => [
                [\Domain\Write\Todo\DuplicateTodoDetection\RenameDuplicateTodoSaga::class, 'processANewTodoWasAdded'],
            ],
        ];

    public static function getMap(): array
    {
        return self::$map;
    }
}