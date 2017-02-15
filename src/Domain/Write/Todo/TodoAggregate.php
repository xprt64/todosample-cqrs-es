<?php


namespace Domain\Write\Todo;


use Domain\Write\Todo\TodoAggregate\Command\AddNewTodo;
use Domain\Write\Todo\TodoAggregate\Command\DeleteTodo;
use Domain\Write\Todo\TodoAggregate\Command\MarkTodoAsDone;
use Domain\Write\Todo\TodoAggregate\Command\UnmarkTodoAsDone;
use Domain\Write\Todo\TodoAggregate\Event\ANewTodoWasAdded;
use Domain\Write\Todo\TodoAggregate\Event\ATodoWasDeleted;
use Domain\Write\Todo\TodoAggregate\Event\ATodoWasMarkedAsDone;
use Domain\Write\Todo\TodoAggregate\Event\ATodoWasUnmarkedAsDone;

class TodoAggregate
{
    private $added = false;
    private $done = false;
    private $deleted;

    public function handleAddNewTodo(AddNewTodo $command)
    {
        if ("" === trim((string)($command->getText()), " ")) {
            throw new \Exception("Invariant: A todo must have a text");
        }

        //idempotent
        if (!$this->added) {
            yield new ANewTodoWasAdded($command->getText());
        }
    }

    public function applyANewTodoWasAdded(ANewTodoWasAdded $event)
    {
        $this->added = true;
    }

    public function handleMarkTodoAsDone(MarkTodoAsDone $command)
    {
        if (!$this->added) {
            throw new \Exception("Item not added");
        }

        //idempotent
        if (!$this->done) {
            yield new ATodoWasMarkedAsDone();
        }
    }

    public function applyATodoWasMarkedAsDone(ATodoWasMarkedAsDone $event)
    {
        $this->done = true;
    }

    public function handleUnmarkTodoAsDone(UnmarkTodoAsDone $command)
    {
        if (!$this->added) {
            throw new \Exception("Item not added");
        }

        //idempotent
        if ($this->done) {
            yield new ATodoWasUnmarkedAsDone();
        }
    }

    public function applyATodoWasUnmarkedAsDone(ATodoWasUnmarkedAsDone $event)
    {
        $this->done = false;
    }

    public function handleDeleteTodo(DeleteTodo $command)
    {
        if (!$this->added) {
            throw new \Exception("Item not added");
        }

        //idempotent
        if (!$this->deleted) {
            yield new ATodoWasDeleted();
        }
    }

    public function applyATodoWasDeleted(ATodoWasDeleted $event)
    {
        $this->deleted = true;
    }
}