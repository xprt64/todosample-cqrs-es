<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

use Dudulina\Command\CommandApplier;
use Dudulina\Command\CommandDispatcher\CommandDispatcherWithValidator;
use Dudulina\Command\CommandDispatcher\ConcurrentProofFunctionCaller;
use Dudulina\Command\CommandDispatcher\DefaultCommandDispatcher;
use Dudulina\Command\CommandDispatcher\SideEffectsDispatcher;
use Dudulina\Command\CommandValidation\CommandValidatorSubscriber;
use Dudulina\Command\MetadataFactory\DefaultMetadataWrapper;
use Dudulina\Event\EventDispatcher\CompositeEventDispatcher;
use Dudulina\Event\EventDispatcher\EventDispatcherBySubscriber;
use Dudulina\Event\EventsApplier\EventsApplierOnAggregate;
use Dudulina\Event\MetadataFactory\DefaultMetadataFactory;
use Dudulina\Saga\SagaEventTrackerRepository;
use Dudulina\Saga\SagasOnlyOnceEventDispatcher;
use Gica\Serialize\ObjectSerializer\ObjectSerializer;
use Infrastructure\Cqrs\EventDispatcher\MutedErrorsDecorator;
use Infrastructure\Orm\GicaToMongoTypeSerializers\MongoSerializer;
use Mongolina\EventsCommit\CommitSerializer;
use Mongolina\EventSerializer;
use Mongolina\FutureEventsStore;
use Mongolina\MongoAggregateAllEventStreamFactory;
use Mongolina\MongoAllEventByClassesStreamFactory;
use Mongolina\MongoEventStore;
use Dudulina\Scheduling\CommandScheduler;
use Dudulina\Scheduling\ScheduledCommandStore;
use Gica\Lib\ObjectToArrayConverter;
use Infrastructure\Cqrs\CommandHandlerSubscriber;
use Infrastructure\Implementations\AuthenticatedIdentityService;
use Infrastructure\Implementations\ReadModelsDatabase;
use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper;

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
                    $container->get(\Infrastructure\Implementations\EventStoreDatabase::class)->selectCollection('eventStore'),
                    $container->get(MongoAggregateAllEventStreamFactory::class),
                    $container->get(MongoAllEventByClassesStreamFactory::class),
                    $container->get(CommitSerializer::class)
                );
            },

            ObjectSerializer::class => function (ContainerInterface $container) {
                return new MongoSerializer();
            },

            \Dudulina\Event\EventSubscriber::class => function (ContainerInterface $container) {
                return $container->get(\Infrastructure\Cqrs\EventSubscriber::class);
            },

            CommandValidatorSubscriber::class => function (ContainerInterface $container) {
                return $container->get(\Infrastructure\Cqrs\CommandValidatorSubscriber::class);
            },

            \Dudulina\Event\EventDispatcher::class => function (ContainerInterface $container) {
                return new CompositeEventDispatcher(
                    new EventDispatcherBySubscriber(
                        $container->get(\Infrastructure\Cqrs\EventSubscriber::class)
                    ),
                    new EventDispatcherBySubscriber(
                        $container->get(\Infrastructure\Cqrs\WriteSideEventSubscriber::class)
                    )
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

            SideEffectsDispatcher::class => function (ContainerInterface $container) {
                return new SideEffectsDispatcher(
                    new CompositeEventDispatcher(
                        new EventDispatcherBySubscriber(
                            $container->get(\Infrastructure\Cqrs\EventSubscriber::class),
                            $container->get(\Psr\Log\LoggerInterface::class)
                        ),
                        new MutedErrorsDecorator(new SagasOnlyOnceEventDispatcher(
                            $container->get(SagaEventTrackerRepository::class),
                            $container->get(\Infrastructure\Cqrs\SagaEventSubscriber::class),
                            $container->get(\Psr\Log\LoggerInterface::class)
                        ))
                    ),
                    $container->get(CommandScheduler::class)
                );
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

            \Dudulina\Command\CommandSubscriber::class => function (ContainerInterface $container) {
                return $container->get(\Infrastructure\Cqrs\CommandHandlerSubscriber::class);
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
