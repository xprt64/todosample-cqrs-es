<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Cqrs\EventStore\Mongo;


class MongoAggregateAllEventStream implements \Gica\Cqrs\EventStore\AggregateEventStream
{
    use \Gica\Cqrs\EventStore\Mongo\EventStreamIteratorTrait;

    /**
     * @var \MongoDB\Collection
     */
    private $collection;
    private $aggregateId;
    private $version;
    /**
     * @var \Gica\Cqrs\EventStore\Mongo\EventSerializer
     */
    private $eventSerializer;
    /**
     * @var string
     */
    private $aggregateClass;

    /** @var  int */
    private $sequence;

    public function __construct(
        \MongoDB\Collection $collection,
        string $aggregateClass,
        $aggregateId,
        \Gica\Cqrs\EventStore\Mongo\EventSerializer $eventSerializer
    )
    {
        $this->collection = $collection;
        $this->aggregateClass = $aggregateClass;
        $this->aggregateId = $aggregateId;
        $this->eventSerializer = $eventSerializer;
        $this->version = $this->fetchLatestVersion($aggregateClass, $aggregateId);
        $this->sequence = $this->fetchLatestSequence();
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        $cursor = $this->getCursorLessThanOrEqualToVersion($this->aggregateClass, $this->aggregateId);

        return $this->getIteratorThatExtractsEventsFromDocument($cursor);
    }

    public function getVersion():int
    {
        return $this->version;
    }

    private function fetchLatestVersion(string $aggregateClass, $aggregateId):int
    {
        return (new \Gica\Cqrs\EventStore\Mongo\LastAggregateVersionFetcher())->fetchLatestVersion($this->collection, $aggregateClass, $aggregateId);
    }

    private function fetchLatestSequence():int
    {
        return (new \Gica\Cqrs\EventStore\Mongo\LastAggregateSequenceFetcher())->fetchLatestSequence($this->collection);
    }

    private function getCursorLessThanOrEqualToVersion(string $aggregateClass, $aggregateId):\MongoDB\Driver\Cursor
    {
        $cursor = $this->collection->find(
            [
                'aggregateId'    => (string)$aggregateId,
                'aggregateClass' => $aggregateClass,
                'version'        => [
                    '$lte' => $this->version,
                ],
            ],
            [
                'sort' => [
                    'sequence' => 1,
                ],
            ]
        );
        return $cursor;
    }

    public function getSequence(): int
    {
        return $this->sequence;
    }
}