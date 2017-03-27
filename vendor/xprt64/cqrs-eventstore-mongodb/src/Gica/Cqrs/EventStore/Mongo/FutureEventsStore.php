<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Cqrs\EventStore\Mongo;


use Gica\Cqrs\Event\EventWithMetaData;
use Gica\Cqrs\Event\ScheduledEvent;
use Gica\Cqrs\Scheduling\ScheduledEventWithMetadata;
use Gica\Types\Guid;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Collection;

class FutureEventsStore implements \Gica\Cqrs\FutureEventsStore
{

    /* @var Collection */
    private $collection;

    public function __construct(
        Collection $collection
    )
    {
        $this->collection = $collection;
    }

    public function loadAndProcessScheduledEvents(callable $eventProcessor)
    {
        while ($scheduledEvent = $this->loadOneEvent()) {
            call_user_func($eventProcessor, $scheduledEvent);
        }
    }

    private function loadOneEvent()
    {
        $document = $this->collection->findOneAndDelete([
            'scheduleAt' => [
                '$lte' => new UTCDateTime(time() * 1000),
            ],
        ], [
            'sort' => ['scheduleAt' => 1],
        ]);

        if (!$document) {
            return null;
        }

        return $this->extractEventWithData($document);
    }

    /**
     * @param EventWithMetaData[] $futureEventsWithMetaData
     */
    public function scheduleEvents($futureEventsWithMetaData)
    {
        foreach ($futureEventsWithMetaData as $eventWithMetaData) {
            /** @var $event ScheduledEvent */
            $event = $eventWithMetaData->getEvent();
            $this->scheduleEvent($eventWithMetaData, $event->getFireDate());
        }
    }

    private function extractEventWithData($document)
    {
        return new ScheduledEventWithMetadata(
            $document['_id'],
            \unserialize($document['eventWithMetaData']));
    }

    public function scheduleEvent(EventWithMetaData $eventWithMetaData, \DateTimeImmutable $date)
    {
        /** @var ScheduledEvent $event */
        $event = $eventWithMetaData->getEvent();

        $messageIdToMongoId = $this->messageIdToMongoId($event->getMessageId());

        $this->collection->updateOne([
            '_id' => $messageIdToMongoId,
        ], [
            '$set' => [
                'scheduleAt'        => new UTCDateTime($date->getTimestamp() * 1000),
                'eventWithMetaData' => \serialize($eventWithMetaData),
            ],
        ], [
            'upsert' => true,
        ]);
    }

    public function createStore()
    {
        $this->collection->createIndex(['scheduleAt' => 1, 'version' => 1]);
    }

    private function messageIdToMongoId($messageId): ObjectID
    {
        if (null === $messageId || '' === $messageId) {
            return new ObjectID(Guid::generate());
        }
        return new ObjectID(Guid::fromFixedString('scheduled-event-' . $messageId));
    }
}