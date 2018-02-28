<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Domain\Write\Todo\DuplicateTodoDetection;


use Domain\Write\Todo\TodoAggregate\Command\RenameTodo;
use Domain\Write\Todo\TodoAggregate\Event\ANewTodoWasAdded;
use Dudulina\Command\CommandDispatcher;

class RenameDuplicateTodoSaga
{

    /**
     * @var ListOfAllTodoNames
     */
    private $listOfAllTodoNames;
    /**
     * @var CommandDispatcher
     */
    private $commandDispatcher;

    public function __construct(
        ListOfAllTodoNames $listOfAllTodoNames,
        CommandDispatcher $commandDispatcher
    )
    {
        $this->listOfAllTodoNames = $listOfAllTodoNames;
        $this->commandDispatcher = $commandDispatcher;
    }

    public function processANewTodoWasAdded(ANewTodoWasAdded $event)
    {
        try {
            $this->listOfAllTodoNames->tryToAddUniqueTodo($event->getText());
        } catch (TodoAlreadyExistsException $exception) {
            $this->commandDispatcher->dispatchCommand(new RenameTodo($event->getId(), $this->getNewName($event->getText())));
        }
    }

    private function getNewName(string $text)
    {
        return $text . ' (automatically renamed by a Saga/Process manager)';
    }
}