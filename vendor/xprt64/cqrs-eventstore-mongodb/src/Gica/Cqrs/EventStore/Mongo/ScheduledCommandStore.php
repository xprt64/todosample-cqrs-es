<?php


namespace Gica\Cqrs\EventStore\Mongo;


use Gica\Cqrs\EventStore\Mongo\ScheduledCommand\ScheduledCommandStoreTrait;
use MongoDB\BSON\UTCDateTime;

class ScheduledCommandStore implements \Gica\Cqrs\Scheduling\ScheduledCommandStore
{
    use ScheduledCommandStoreTrait;

    public function loadAndProcessScheduledCommands(callable $eventProcessor/** function(ScheduledCommand $scheduledCommand) */)
    {
        while (($command = $this->loadOneCommand())) {
            call_user_func($eventProcessor, $command);
        }
    }

    private function loadOneCommand()
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

        return $this->hydrateCommand($document);
    }

    private function hydrateCommand($document)
    {
        return unserialize($document['command']);
    }

    public function cancelCommand($commandId)
    {
        $this->collection->deleteOne([
            '_id' => $this->messageIdToMongoId($commandId),
        ]);
    }
}