<?php


namespace Domain\Write\Todo;


use Domain\Write\Todo\TodoAggregate\Command\AddNewTodo;
use Domain\Write\Todo\TodoAggregate\Command\DeleteTodo;
use Domain\Write\Todo\TodoAggregate\Command\MarkTodoAsDone;
use Domain\Write\Todo\TodoAggregate\Command\RenameTodo;
use Domain\Write\Todo\TodoAggregate\Command\UnmarkTodoAsDone;
use Domain\Write\Todo\TodoAggregate\Event\ANewTodoWasAdded;
use Domain\Write\Todo\TodoAggregate\Event\ANewTodoWasRenamed;
use Domain\Write\Todo\TodoAggregate\Event\ATodoWasDeleted;
use Domain\Write\Todo\TodoAggregate\Event\ATodoWasMarkedAsDone;
use Domain\Write\Todo\TodoAggregate\Event\ATodoWasUnmarkedAsDone;

class TodoAggregate
{
    private $added = false;
    private $text = null;
    private $done = false;
    private $deleted;

    public function handleAddNewTodo(AddNewTodo $command)
    {
        if ($this->isTodoTextInvalid($command->getText())) {
            throw new \InvalidArgumentException("Invariant: A todo must have a text");
        }

        //idempotent
        if (!$this->added) {
            yield new ANewTodoWasAdded($command->getId(), $command->getText());
        }
    }

    public function applyANewTodoWasAdded(ANewTodoWasAdded $event)
    {
        $this->added = true;
        $this->text = $event->getText();
    }

    public function handleRenameTodo(RenameTodo $command)
    {
        if ($this->isTodoTextInvalid($command->getText())) {
            throw new \InvalidArgumentException("Invariant: A todo must have a text");
        }

        //idempotent
        if ($this->text !== $command->getText()) {
            yield new ANewTodoWasRenamed($command->getId(), $command->getText());
        }
    }

    public function applyANewTodoWasRenamed(ANewTodoWasRenamed $event)
    {
        $this->text = $event->getText();
    }

    public function handleMarkTodoAsDone(MarkTodoAsDone $command)
    {
        if (!$this->added) {
            throw new \InvalidArgumentException("Item not added");
        }

        //idempotent
        if (!$this->done) {
            yield new ATodoWasMarkedAsDone($command->getId());
        }
    }

    public function applyATodoWasMarkedAsDone(ATodoWasMarkedAsDone $event)
    {
        $this->done = true;
    }

    public function handleUnmarkTodoAsDone(UnmarkTodoAsDone $command)
    {
        if (!$this->added) {
            throw new \InvalidArgumentException("Item not added");
        }

        //idempotent
        if ($this->done) {
            yield new ATodoWasUnmarkedAsDone($command->getId());
        }
    }

    public function applyATodoWasUnmarkedAsDone(ATodoWasUnmarkedAsDone $event)
    {
        $this->done = false;
    }

    public function handleDeleteTodo(DeleteTodo $command)
    {
        if (!$this->added) {
            throw new \InvalidArgumentException("Item not added");
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

    private function isTodoTextInvalid($text): bool
    {
        return "" === trim((string)($text), " ");
    }
}