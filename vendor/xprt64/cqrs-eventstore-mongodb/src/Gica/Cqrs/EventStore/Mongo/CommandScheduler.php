<?php


namespace Gica\Cqrs\EventStore\Mongo;


use Gica\Cqrs\EventStore\Mongo\ScheduledCommand\ScheduledCommandStoreTrait;
use Gica\Cqrs\Scheduling\ScheduledCommand;
use MongoDB\BSON\UTCDateTime;

class CommandScheduler implements \Gica\Cqrs\Scheduling\CommandScheduler
{
    use ScheduledCommandStoreTrait;

    /**
     * @param ScheduledCommand[] $scheduledCommands
     */
    public function scheduleCommands($scheduledCommands)
    {
        foreach ($scheduledCommands as $command) {
            $this->scheduleCommand($command, '', '');
        }
    }

    public function scheduleCommand(ScheduledCommand $scheduledCommand, string $aggregateClass, $aggregateId)
    {
        $messageIdToMongoId = $this->messageIdToMongoId($scheduledCommand->getMessageId());

        $this->collection->updateOne([
            '_id' => $messageIdToMongoId,
        ], [
            '$set' => [
                '_id'        => $messageIdToMongoId,
                'scheduleAt' => new UTCDateTime($scheduledCommand->getFireDate()->getTimestamp() * 1000),
                'command'    => \serialize($scheduledCommand),
                'aggregate'  => [
                    'id'    => (string)$aggregateId,
                    'class' => $aggregateClass,
                ],
            ],
        ], [
            'upsert' => true,
        ]);
    }

    public function cancelCommand($commandId)
    {
        $this->collection->deleteOne([
            '_id' => $this->messageIdToMongoId($commandId),
        ]);
    }
}