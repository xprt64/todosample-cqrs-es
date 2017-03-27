<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Gica\Cqrs\EventStore\Mongo\ScheduledCommand;

use Gica\Cqrs\Scheduling\ScheduledCommand;
use Gica\Types\Guid;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;

trait ScheduledCommandStoreTrait
{

    /* @var \MongoDB\Collection */
    private $collection;

    public function __construct(
        \MongoDB\Collection $collection
    )
    {
        $this->collection = $collection;
    }

    private function messageIdToMongoId($messageId): ObjectID
    {
        if (null === $messageId || '' === $messageId) {
            return new ObjectID(Guid::generate());
        }

        return new ObjectID(Guid::fromFixedString('scheduled-message-' . $messageId));
    }

    public function createStore()
    {
        $this->collection->createIndex(['scheduleAt' => 1, 'version' => 1]);
    }

    public function dropStore()
    {
        $this->collection->drop();
    }
}