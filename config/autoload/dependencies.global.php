<?php
use Gica\Cqrs\Command\CommandApplier;
use Gica\Cqrs\Command\CommandDispatcher\CommandDispatcherWithValidator;
use Gica\Cqrs\Command\CommandDispatcher\ConcurrentProofFunctionCaller;
use Gica\Cqrs\Command\CommandDispatcher\DefaultCommandDispatcher;
use Gica\Cqrs\Command\CommandValidation\CommandValidatorSubscriber;
use Gica\Cqrs\Command\MetadataFactory\DefaultMetadataWrapper;
use Gica\Cqrs\Event\EventDispatcher\CompositeEventDispatcher;
use Gica\Cqrs\Event\EventDispatcher\EventDispatcherBySubscriber;
use Gica\Cqrs\Event\EventsApplier\EventsApplierOnAggregate;
use Gica\Cqrs\Event\MetadataFactory\DefaultMetadataFactory;
use Gica\Cqrs\EventStore\Mongo\EventSerializer;
use Gica\Cqrs\EventStore\Mongo\FutureEventsStore;
use Gica\Cqrs\EventStore\Mongo\MongoEventStore;
use Gica\Cqrs\Scheduling\CommandScheduler;
use Gica\Cqrs\Scheduling\ScheduledCommandStore;
use Gica\Lib\ObjectToArrayConverter;
use Infrastructure\Cqrs\CommandHandlerSubscriber;
use Infrastructure\Implementations\AuthenticatedIdentityService;
use Infrastructure\Implementations\ReadModelsDatabase;
use Interop\Container\ContainerInterface;
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
            Helper\ServerUrlHelper::class               => Helper\ServerUrlHelper::class,
            \Gica\FileSystem\FileSystemInterface::class => \Gica\FileSystem\OperatingSystemFileSystem::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories'          => [
            Application::class                      => ApplicationFactory::class,
            Helper\UrlHelper::class                 => Helper\UrlHelperFactory::class,
            \Gica\Dependency\AbstractFactory::class => function (\Interop\Container\ContainerInterface $container) {
                return new \Gica\Dependency\ConstructorAbstractFactory($container);
            },

            \Domain\Read\Dependency\Database\ReadModelsDatabase::class => function (ContainerInterface $container) {
                return $container->get(ReadModelsDatabase::class);
            },

            \Gica\Cqrs\EventStore::class => function (ContainerInterface $container) {
                return new MongoEventStore(
                    $container->get(\Infrastructure\Implementations\EventStoreDatabase::class)->selectCollection('eventStore'),
                    new EventSerializer(),
                    new ObjectToArrayConverter(),
                    $container->get(\Gica\Cqrs\EventStore\Mongo\EventFromCommitExtractor::class)
                );
            },

            \Gica\Cqrs\FutureEventsStore::class => function (ContainerInterface $container) {
                return new FutureEventsStore(
                    $container->get(\Infrastructure\Implementations\EventStoreDatabase::class)->selectCollection('futureEventStore'));
            },

            \Gica\Cqrs\Event\EventSubscriber::class => function (ContainerInterface $container) {
                return $container->get(\Infrastructure\Cqrs\EventSubscriber::class);
            },

            CommandValidatorSubscriber::class => function (ContainerInterface $container) {
                return $container->get(\Infrastructure\Cqrs\CommandValidatorSubscriber::class);
            },

            \Gica\Cqrs\Event\EventDispatcher::class => function (ContainerInterface $container) {
                return new CompositeEventDispatcher(
                    new EventDispatcherBySubscriber(
                        $container->get(\Infrastructure\Cqrs\EventSubscriber::class)
                    ),
                    new EventDispatcherBySubscriber(
                        $container->get(\Infrastructure\Cqrs\WriteSideEventSubscriber::class)
                    )
                );
            },

            \Gica\Cqrs\Command\CommandDispatcher::class => function (ContainerInterface $container) {
                return new CommandDispatcherWithValidator(
                    new DefaultCommandDispatcher(
                        new CommandHandlerSubscriber(),
                        $container->get(\Gica\Cqrs\Event\EventDispatcher::class),
                        new CommandApplier(),
                        $container->get(\Gica\Cqrs\Aggregate\AggregateRepository::class),
                        new ConcurrentProofFunctionCaller(),
                        new EventsApplierOnAggregate,
                        new DefaultMetadataFactory(new AuthenticatedIdentityService()),
                        new DefaultMetadataWrapper(),
                        $container->get(\Gica\Cqrs\FutureEventsStore::class),
                        $container->get(\Gica\Cqrs\Scheduling\CommandScheduler::class)
                    ),
                    $container->get(\Gica\Cqrs\Command\CommandValidator::class));
            },

            CommandScheduler::class => function (ContainerInterface $container) {
                $cqrs = $container->get(\Infrastructure\Implementations\EventStoreDatabase::class);
                return new \Gica\Cqrs\EventStore\Mongo\CommandScheduler(
                    $cqrs->selectCollection('scheduledCommands'));
            },

            ScheduledCommandStore::class => function (ContainerInterface $container) {
                $cqrs = $container->get(\Infrastructure\Implementations\EventStoreDatabase::class);
                return new \Gica\Cqrs\EventStore\Mongo\ScheduledCommandStore(
                    $cqrs->selectCollection('scheduledCommands'));
            },

        ],
        'abstract_factories' => [
            \Infrastructure\Implementations\AbstractFactory::class,
        ],
    ],
];
