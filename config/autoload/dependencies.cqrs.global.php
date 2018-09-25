<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

use Dudulina\Aggregate\AggregateRepository;
use Dudulina\Aggregate\EventSourcedAggregateRepository;
use Dudulina\Command\CommandDispatcher\CommandDispatcherWithValidator;
use Dudulina\Command\CommandDispatcher\DefaultCommandDispatcher;
use Dudulina\Command\CommandDispatcher\SideEffectsDispatcher;
use Dudulina\Command\CommandDispatcher\SideEffectsDispatcher\DefaultSideEffectsDispatcher;
use Dudulina\Command\CommandValidation\CommandValidatorSubscriberByMap;
use Dudulina\Command\MetadataFactory\DefaultMetadataWrapper;
use Dudulina\Event\EventDispatcher\CompositeEventDispatcher;
use Dudulina\Event\EventDispatcher\EventDispatcherBySubscriber;
use Dudulina\Event\EventSubscriber\EventSubscriberByMap;
use Dudulina\Event\MetadataFactory\DefaultMetadataFactory;
use Dudulina\Query\Answerer;
use Dudulina\Query\Answerer\DefaultAnswerer;
use Dudulina\Query\Asker;
use Dudulina\Query\Asker\DefaultAsker;
use Dudulina\Saga\SagaEventTrackerRepository;
use Dudulina\Saga\SagasOnlyOnceEventDispatcher;
use Dudulina\Scheduling\CommandScheduler;
use Dudulina\Scheduling\ScheduledCommandStore;
use Gica\Serialize\ObjectSerializer\ObjectSerializer;
use Infrastructure\Cqrs\EventDispatcher\MutedErrorsDecorator;
use Infrastructure\Implementations\ReadModelsDatabase;
use Infrastructure\Orm\GicaToMongoTypeSerializers\MongoSerializer;
use Mongolina\EventsCommit\CommitSerializer;
use Mongolina\MongoAggregateAllEventStreamFactory;
use Mongolina\MongoAllEventByClassesStreamFactory;
use Mongolina\MongoEventStore;
use Psr\Container\ContainerInterface;

return [
    // Provides application-wide services.
    // We recommend using fully-qualified class names whenever possible as
    // service names.
    'dependencies' => [
        // Use 'invokables' for constructor-less services, or services that do
        // not require arguments to the constructor. Map a service name to the
        // class name.
        'invokables'         => [
            // Fully\Qualified\InterfaceName::class => Fully\Qualified\ClassName::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories'          => [
            \Dudulina\Command\CommandDispatcher\AuthenticatedIdentityReaderService::class => function () {
                return new Infrastructure\Implementations\AuthenticatedIdentityService();
            },

            \Domain\Read\Dependency\Database\ReadModelsDatabase::class => function (ContainerInterface $container) {
                return $container->get(ReadModelsDatabase::class);
            },

            \Dudulina\EventStore::class => function (ContainerInterface $container) {
                return new MongoEventStore(
                    $container->get(\Infrastructure\Implementations\EventStoreDatabase::class)
                        ->selectCollection('eventStore'),
                    $container->get(MongoAggregateAllEventStreamFactory::class),
                    $container->get(MongoAllEventByClassesStreamFactory::class),
                    $container->get(CommitSerializer::class)
                );
            },
            AggregateRepository::class  => function (ContainerInterface $container) {
                return $container->get(EventSourcedAggregateRepository::class);
            },

            ObjectSerializer::class => function (ContainerInterface $container) {
                return new MongoSerializer();
            },

            \Dudulina\Event\EventDispatcher::class => function (ContainerInterface $container) {
                return new CompositeEventDispatcher(
                    new EventDispatcherBySubscriber(
                        new EventSubscriberByMap($container, \EventListenersMap::getMap())
                    ),
                    new EventDispatcherBySubscriber(
                        new EventSubscriberByMap($container, \SagaEventProcessorsMap::getMap())
                    )
                );
            },

            \Dudulina\Command\CommandSubscriber::class => function () {
                return new \Dudulina\Command\CommandSubscriber\CommandSubscriberByMap(\CommandHandlersMap::getMap());
            },

            \Dudulina\Command\CommandValidator::class => function (ContainerInterface $container) {
                return new \Dudulina\Command\CommandValidation\CommandValidatorBySubscriber(
                    new CommandValidatorSubscriberByMap(\CommandValidatorSubscriber::getMap()),
                    $container
                );
            },

            \Dudulina\Command\CommandDispatcher::class => function (ContainerInterface $container) {
                return new CommandDispatcherWithValidator(
                    $container->get(DefaultCommandDispatcher::class),
                    $container->get(\Dudulina\Command\CommandValidator::class)
                );
                //   ,$container->get(\Crm\Read\Graph\MessagesFlow::class)
                //);
            },

            SideEffectsDispatcher::class             => function (ContainerInterface $container) {
                return new DefaultSideEffectsDispatcher(
                    new CompositeEventDispatcher(
                        new EventDispatcherBySubscriber(
                            new EventSubscriberByMap($container, \EventListenersMap::getMap()),
                            $container->get(\Psr\Log\LoggerInterface::class)
                        ),
                        new MutedErrorsDecorator(new SagasOnlyOnceEventDispatcher(
                            $container->get(SagaEventTrackerRepository::class),
                            new EventSubscriberByMap($container, \SagaEventProcessorsMap::getMap()),
                            $container->get(\Psr\Log\LoggerInterface::class)
                        ))
                    ),
                    $container->get(CommandScheduler::class)
                );
            },
            Asker::class                             => function (ContainerInterface $container) {
                return new DefaultAsker(
                    $container,
                    new \Dudulina\Query\AnswererResolver\ByMap(\QueryHandlersMap::getMap()),
                    new \Dudulina\Query\AskerResolver\ByMap(\QueryAskersMap::getMap())
                );
            },
            Answerer::class                          => function (ContainerInterface $container) {
                return new DefaultAnswerer(
                    $container,
                    new \Dudulina\Query\AskerResolver\ByMap(\QueryAskersMap::getMap())
                );
            },
            \Psr\Container\ContainerInterface::class => function (ContainerInterface $container) {
                return $container;
            },

            SagaEventTrackerRepository::class => function (ContainerInterface $container) {
                $cqrs = $container->get(\Infrastructure\Implementations\EventStoreDatabase::class);
                return new \Mongolina\Saga\SagaEventTrackerRepository(
                    $cqrs->selectCollection('sagaEventTrackerRepositoryStarted')
                );
            },

            \Dudulina\Event\MetadataFactory::class => function (ContainerInterface $container) {
                return $container->get(DefaultMetadataFactory::class);
            },

            \Dudulina\Command\MetadataWrapper::class => function (ContainerInterface $container) {
                return new DefaultMetadataWrapper();
            },

            CommandScheduler::class => function (ContainerInterface $container) {
                $cqrs = $container->get(\Infrastructure\Implementations\EventStoreDatabase::class);
                return new \Mongolina\CommandScheduler(
                    $cqrs->selectCollection('scheduledCommands'));
            },

            ScheduledCommandStore::class => function (ContainerInterface $container) {
                $cqrs = $container->get(\Infrastructure\Implementations\EventStoreDatabase::class);
                return new \Mongolina\ScheduledCommandStore(
                    $cqrs->selectCollection('scheduledCommands'));
            },

        ],
        'abstract_factories' => [
        ],
    ],
];
