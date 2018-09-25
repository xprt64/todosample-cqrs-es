<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Domain\Read\Todo;


use Domain\Query\Todo\WhatIsTheStatusOfTheTodo;
use Domain\Query\Todo\WhatIsTheTitleOfTheTodo;
use Domain\Read\Dependency\Database\ReadModelsDatabase;
use Domain\Read\Todo\TodoList\Todo;
use Domain\Read\Todo\TodoList\TodoFactory;
use Domain\Write\Todo\TodoAggregate\Event\ANewTodoWasAdded;
use Domain\Write\Todo\TodoAggregate\Event\ANewTodoWasRenamed;
use Domain\Write\Todo\TodoAggregate\Event\ATodoWasDeleted;
use Domain\Write\Todo\TodoAggregate\Event\ATodoWasMarkedAsDone;
use Domain\Write\Todo\TodoAggregate\Event\ATodoWasUnmarkedAsDone;
use Dudulina\Event\MetaData;
use Dudulina\Query\Asker;
use Dudulina\ReadModel\ReadModelInterface;
use Gica\Iterator\IteratorTransformer\IteratorMapper;
use MongoDB\BSON\UTCDateTime;

class TodoList implements ReadModelInterface
{

    /**
     * @var ReadModelsDatabase
     */
    private $database;
    /**
     * @var Asker
     */
    private $asker;

    public function __construct(
        ReadModelsDatabase $database,
    Asker $asker
    )
    {
        $this->database = $database;
        $this->asker = $asker;
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
        // we ask a question and the answer will come to the self::whenAnsweredWhatIsTheStatusOfTheTodo
        $this->asker->askAndNotifyAsker(new WhatIsTheStatusOfTheTodo($event->getId()), $this);
    }

    /**
     * We don't listen to ANewTodoWasRenamed events, we are not interested in the history of renamings.
     * Instead, we listen to a query, and get only the latest modification when rebuilding.
     * This method is called every time the Answerer decides that the answer changes
     * @see \Domain\Read\Todo\TodoDetails::whatIsTheStatusOfTheTodo()
     * @QueryAsker
     */
    public function whenAnsweredWhatIsTheTitleOfTheTodo(WhatIsTheTitleOfTheTodo $question): void
    {
        $this->getCollection()->updateOne([
            '_id' => $question->getId(),
        ], [
            '$set' => [
                'text' => $question->getAnswer(),
            ],
        ]);
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
    }

    /**
     * We don't listen to ATodoWasMarkedAsDone or ATodoWasUnmarkedAsDone. Instead, we listen to a query, just
     * to demonstrate how to use queries
     * @see \Domain\Read\Todo\TodoDetails::whatIsTheStatusOfTheTodo()
     * @QueryAsker
     */
    public function whenAnsweredWhatIsTheStatusOfTheTodo(WhatIsTheStatusOfTheTodo $question): void
    {
        $this->getCollection()->updateOne([
            '_id' => $question->getId(),
        ], [
            '$set' => [
                'done' => $question->isDone(),
            ],
        ]);
    }

    public function onATodoWasDeleted(ATodoWasDeleted $event, MetaData $metaData)
    {
        $this->getCollection()->deleteOne([
            '_id' => (string)$metaData->getAggregateId(),
        ]);
    }

    /**
     * @return Todo[]
     */
    public function getAllTodos()
    {
        $cursor = $this->getCollection()->find([], [
            '$sort' => [
                'dateAdded' => 1,
            ],
        ]);

        $mapper = new IteratorMapper(new TodoFactory());

        return $mapper->asArray($cursor);
    }

    public function getTodoText(string $id):?string
    {
        $document = $this->getCollection()->findOne([
            '_id' => $id,
        ]);

        return $document ? $document['text'] : null;
    }

}