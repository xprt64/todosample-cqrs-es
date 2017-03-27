<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace tests\unit\Gica\Cqrs\EventStore\Mongo;


use Gica\Cqrs\EventStore\Mongo\CommandScheduler;
use Gica\Cqrs\EventStore\Mongo\ScheduledCommandStore;
use Gica\Cqrs\Scheduling\ScheduledCommand;

class ScheduledCommandStoreTest extends \PHPUnit_Framework_TestCase
{

    public function test_appendEventsForAggregate()
    {
        $databaseName = 'cqrs';

        $client = new \MongoDB\Client('mongodb://testusername:testpasswd@localhost:27017/' . $databaseName);

        $db = $client->selectDatabase($databaseName);

        $collection = $db->selectCollection('scheduledCommandStore');

        $commandScheduler = new CommandScheduler(
            $collection);

        $scheduledCommandStore = new ScheduledCommandStore(
            $collection);

        $scheduledCommandStore->dropStore();
        $scheduledCommandStore->createStore();

        $command = $this->getMockBuilder(ScheduledCommand::class)
            ->getMock();

        $command->method('getFireDate')
            ->willReturn(new \DateTimeImmutable());

        $command->method('getMessageId')
            ->willReturn('1234');

        $commandScheduler->scheduleCommand($command);

        $this->assertCount(1, $collection->find()->toArray());

        $processor = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['__invoke'])
            ->getMock();

        $processor->expects($this->once())
            ->method('__invoke')
            ->with($command);

        $scheduledCommandStore->loadAndProcessScheduledCommands($processor);

        $this->assertCount(0, $collection->find()->toArray());
    }

    public function test_appendEventsForAggregateDuplicateCommand()
    {
        $databaseName = 'cqrs';

        $client = new \MongoDB\Client('mongodb://testusername:testpasswd@localhost:27017/' . $databaseName);

        $db = $client->selectDatabase($databaseName);

        $collection = $db->selectCollection('scheduledCommandStore');


        $commandScheduler = new CommandScheduler(
            $collection);

        $commandScheduler->dropStore();
        $commandScheduler->createStore();

        $command = $this->getMockBuilder(ScheduledCommand::class)
            ->getMock();

        $command->method('getFireDate')
            ->willReturn(new \DateTimeImmutable());

        $command->method('getMessageId')
            ->willReturn('1234');

        $commandScheduler->scheduleCommands([$command, $command, $command, $command]);

        $this->assertCount(1, $collection->find()->toArray());
    }
}
