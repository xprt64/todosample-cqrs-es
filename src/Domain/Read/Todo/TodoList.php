<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Domain\Read\Todo;


use Domain\Read\Dependency\Database\ReadModelsDatabase;
use Domain\Read\Todo\TodoList\Todo;
use Domain\Read\Todo\TodoList\TodoFactory;
use Domain\Write\Todo\TodoAggregate\Event\ANewTodoWasAdded;
use Domain\Write\Todo\TodoAggregate\Event\ANewTodoWasRenamed;
use Domain\Write\Todo\TodoAggregate\Event\ATodoWasDeleted;
use Domain\Write\Todo\TodoAggregate\Event\ATodoWasMarkedAsDone;
use Domain\Write\Todo\TodoAggregate\Event\ATodoWasUnmarkedAsDone;
use Dudulina\Event\MetaData;
use Dudulina\ReadModel\ReadModelInterface;
use Gica\Iterator\IteratorTransformer\IteratorMapper;
use MongoDB\BSON\UTCDateTime;

class TodoList implements ReadModelInterface
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

    public function onATodoWasUnmarkedAsDone(ATodoWasUnmarkedAsDone $event, MetaData $metaData)
    {
        $this->getCollection()->updateOne([
            '_id' => (string)$metaData->getAggregateId(),
        ], [
            '$set' => [
                'done' => false,
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