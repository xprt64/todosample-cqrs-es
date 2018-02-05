<?php
use Dudulina\Command\CommandApplier;
use Dudulina\Command\CommandDispatcher\CommandDispatcherWithValidator;
use Dudulina\Command\CommandDispatcher\ConcurrentProofFunctionCaller;
use Dudulina\Command\CommandDispatcher\DefaultCommandDispatcher;
use Dudulina\Command\CommandValidation\CommandValidatorSubscriber;
use Dudulina\Command\MetadataFactory\DefaultMetadataWrapper;
use Dudulina\Event\EventDispatcher\CompositeEventDispatcher;
use Dudulina\Event\EventDispatcher\EventDispatcherBySubscriber;
use Dudulina\Event\EventsApplier\EventsApplierOnAggregate;
use Dudulina\Event\MetadataFactory\DefaultMetadataFactory;
use Mongolina\EventSerializer;
use Mongolina\FutureEventsStore;
use Mongolina\MongoEventStore;
use Dudulina\Scheduling\CommandScheduler;
use Dudulina\Scheduling\ScheduledCommandStore;
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

            \Dudulina\EventStore::class => function (ContainerInterface $container) {
                return new MongoEventStore(
                    $container->get(\Infrastructure\Implementations\EventStoreDatabase::class)->selectCollection('eventStore'),
                    new EventSerializer(),
                    new ObjectToArrayConverter(),
                    $container->get(\Mongolina\EventFromCommitExtractor::class)
                );
            },

            \Dudulina\FutureEventsStore::class => function (ContainerInterface $container) {
                return new FutureEventsStore(
                    $container->get(\Infrastructure\Implementations\EventStoreDatabase::class)->selectCollection('futureEventStore'));
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
                    new DefaultCommandDispatcher(
                        new CommandHandlerSubscriber(),
                        $container->get(\Dudulina\Event\EventDispatcher::class),
                        new CommandApplier(),
                        $container->get(\Dudulina\Aggregate\AggregateRepository::class),
                        new ConcurrentProofFunctionCaller(),
                        new EventsApplierOnAggregate,
                        new DefaultMetadataFactory(new AuthenticatedIdentityService()),
                        new DefaultMetadataWrapper(),
                        $container->get(\Dudulina\FutureEventsStore::class),
                        $container->get(\Dudulina\Scheduling\CommandScheduler::class)
                    ),
                    $container->get(\Dudulina\Command\CommandValidator::class));
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
            \Infrastructure\Implementations\AbstractFactory::class,
        ],
    ],
];
