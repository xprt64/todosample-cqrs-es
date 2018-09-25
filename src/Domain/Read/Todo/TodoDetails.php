<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Domain\Read\Todo;


use Domain\Query\Todo\WhatIsTheStatusOfTheTodo;
use Domain\Query\Todo\WhatIsTheTitleOfTheTodo;
use Domain\Read\Dependency\Database\ReadModelsDatabase;
use Domain\Write\Todo\TodoAggregate\Event\ANewTodoWasAdded;
use Domain\Write\Todo\TodoAggregate\Event\ANewTodoWasRenamed;
use Domain\Write\Todo\TodoAggregate\Event\ATodoWasDeleted;
use Domain\Write\Todo\TodoAggregate\Event\ATodoWasMarkedAsDone;
use Domain\Write\Todo\TodoAggregate\Event\ATodoWasUnmarkedAsDone;
use Dudulina\Event\MetaData;
use Dudulina\Query\Answerer;
use Dudulina\ReadModel\ReadModelInterface;
use MongoDB\BSON\UTCDateTime;

class TodoDetails implements ReadModelInterface
{

    /**
     * @var ReadModelsDatabase
     */
    private $database;
    /**
     * @var Answerer
     */
    private $answerer;

    public function __construct(
        ReadModelsDatabase $database,
        Answerer $answerer
    )
    {
        $this->database = $database;
        $this->answerer = $answerer;
    }

    private function getCollection()
    {
        return $this->database->selectCollection('TodoList');
    }

    public function clearModel()
    {
        $this->getCollection()->drop();
    }

    public function createModel()
    {
    }

    public function onANewTodoWasAdded(ANewTodoWasAdded $event, MetaData $metaData)
    {
        $this->getCollection()->insertOne([
            '_id'       => (string)$metaData->getAggregateId(),
            'text'      => $event->getText(),
            'done'      => false,
            'dateAdded' => new UTCDateTime($metaData->getDateCreated()->getTimestamp() * 1000),
        ]);
        $this->answerer->answer((new WhatIsTheTitleOfTheTodo($event->getId()))->withAnswer($event->getText()));
        $this->answerer->answer((new WhatIsTheStatusOfTheTodo($event->getId()))->withAnswer(false));
    }

    public function onATodoWasMarkedAsDone(ATodoWasMarkedAsDone $event, MetaData $metaData)
    {
        $this->getCollection()->updateOne([
            '_id' => (string)$metaData->getAggregateId(),
        ], [
            '$set' => [
                'done' => true,
            ],
        ]);
        //we notify the askers about the new status of the Todo
        $this->answerer->answer((new WhatIsTheStatusOfTheTodo($event->getId()))->withAnswer(true));
    }

    public function onANewTodoWasRenamed(ANewTodoWasRenamed $event)
    {
        $this->getCollection()->updateOne([
            '_id' => (string)$event->getId(),
        ], [
            '$set' => [
                'text' => $event->getText(),
            ],
        ]);
        $this->answerer->answer((new WhatIsTheTitleOfTheTodo($event->getId()))->withAnswer($event->getText()));
    }

    public function onATodoWasUnmarkedAsDone(ATodoWasUnmarkedAsDone $event, MetaData $metaData)
    {
        $this->getCollection()->updateOne([
            '_id' => (string)$metaData->getAggregateId(),
        ], [
            '$set' => [
                'done' => false,
            ],
        ]);
        $this->answerer->answer((new WhatIsTheStatusOfTheTodo($event->getId()))->withAnswer(false));
    }

    public function onATodoWasDeleted(ATodoWasDeleted $event, MetaData $metaData)
    {
        $this->getCollection()->deleteOne([
            '_id' => (string)$metaData->getAggregateId(),
        ]);
    }

    public function getTodoText(string $id): ?string
    {
        $document = $this->getCollection()->findOne([
            '_id' => $id,
        ]);

        return $document ? $document['text'] : null;
    }

    /**
     * @QueryHandler
     */
    public function whatIsTheStatusOfTheTodo(WhatIsTheStatusOfTheTodo $question): WhatIsTheStatusOfTheTodo
    {
        $todo = $this->loadTodo($question->getId());
        if ($todo && isset($todo['done'])) {
            return $question->withAnswer($todo['done']); //answered question
        }
        return $question;//not answered question
    }

    /**
     * @QueryHandler
     */
    public function whatIsTheTitleOfTheTodo(WhatIsTheTitleOfTheTodo $question): WhatIsTheTitleOfTheTodo
    {
        return $question->withAnswer($this->getTodoText($question->getId())); //answered question
    }

    private function loadTodo(string $id): ?array
    {
        return $this->getCollection()->findOne(['_id' => $id]);
    }
}