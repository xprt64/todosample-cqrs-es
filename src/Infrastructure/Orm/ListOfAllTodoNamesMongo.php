<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Orm;


use Domain\Read\Dependency\Database\ReadModelsDatabase;
use Domain\Write\Todo\DuplicateTodoDetection\ListOfAllTodoNames;
use Domain\Write\Todo\DuplicateTodoDetection\TodoAlreadyExistsException;
use MongoDB\Driver\Exception\BulkWriteException;

class ListOfAllTodoNamesMongo implements ListOfAllTodoNames
{

    /**
     * @var ReadModelsDatabase
     */
    private $database;

    public function __construct(
        ReadModelsDatabase $database
    )
    {
        $this->database = $database;
    }

    private function getCollection()
    {
        return $this->database->selectCollection('ListOfAllTodoNames');
    }

    public function tryToAddUniqueTodo(string $todoTitle): void
    {
        try {
            $this->getCollection()->insertOne([
                '_id' => $todoTitle,
            ]);
        } catch (BulkWriteException $exception) {
            throw new TodoAlreadyExistsException($exception->getMessage());
        }
    }
}