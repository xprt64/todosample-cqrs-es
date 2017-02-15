<?php
use Gica\Cqrs\Command\CommandDispatcher\ConcurrentProofFunctionCaller;
use Gica\Cqrs\Command\CommandValidation\CommandValidatorSubscriber;
use Gica\Cqrs\Event\EventDispatcher\CompositeEventDispatcher;
use Gica\Cqrs\Event\EventDispatcher\EventDispatcherBySubscriber;
use Gica\Cqrs\Event\EventsApplier\EventsApplierOnAggregate;
use Gica\Cqrs\EventStore\Mongo\EventSerializer;
use Gica\Cqrs\EventStore\Mongo\FutureEventsStore;
use Gica\Cqrs\EventStore\Mongo\MongoEventStore;
use Infrastructure\Implementations\AuthenticatedIdentityService;
use Infrastructure\Implementations\EventStoreDatabase;
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
            Application::class                                    => ApplicationFactory::class,
            Helper\UrlHelper::class                               => Helper\UrlHelperFactory::class,
            \Gica\Dependency\AbstractFactory::class                      => function (\Interop\Container\ContainerInterface $container) {
                return new \Gica\Dependency\ConstructorAbstractFactory($container);
            },
            \Domain\Dependency\Database\EventStoreDatabase::class => function (ContainerInterface $container) {
                return $container->get(EventStoreDatabase::class);
            },
            \Domain\Dependency\Database\ReadModelsDatabase::class => function (ContainerInterface $container) {
                return $container->get(ReadModelsDatabase::class);
            },

            \Gica\Cqrs\EventStore::class =>  function (ContainerInterface $container) {
                return new MongoEventStore(
                    $container->get(\Domain\Dependency\Database\EventStoreDatabase::class)->selectCollection('eventStore'),
                    new EventSerializer()
                );
            },

            \Gica\Cqrs\FutureEventsStore::class =>  function (ContainerInterface $container) {
                return new FutureEventsStore(
                    $container->get(\Domain\Dependency\Database\EventStoreDatabase::class)->selectCollection('futureEventStore'));
            },

            \Gica\Cqrs\Command\CommandSubscriber::class => function (ContainerInterface $container) {
                return $container->get(\Domain\Cqrs\CommandHandlerSubscriber::class);
            },

            \Gica\Cqrs\Event\EventSubscriber::class => function (ContainerInterface $container) {
                return $container->get(\Domain\Cqrs\EventSubscriber::class);
            },

            CommandValidatorSubscriber::class => function (ContainerInterface $container) {
                return $container->get(\Domain\Cqrs\CommandValidatorSubscriber::class);
            },

            \Gica\Cqrs\Command\CommandDispatcher::class => function (ContainerInterface $container) {
                return new \Gica\Cqrs\Command\CommandDispatcher(
                    $container->get(\Gica\Cqrs\Command\CommandSubscriber::class),
                    new CompositeEventDispatcher(
                        new EventDispatcherBySubscriber(
                            $container->get(\Domain\Cqrs\EventSubscriber::class)
                        ),
                        new EventDispatcherBySubscriber(
                            $container->get(\Domain\Cqrs\WriteSideEventSubscriber::class)
                        )
                    ),
                    $container->get(\Gica\Cqrs\Command\CommandApplier::class),
                    $container->get(\Gica\Cqrs\Aggregate\AggregateRepository::class),
                    new ConcurrentProofFunctionCaller(),
                    $container->get(\Gica\Cqrs\Command\CommandValidator::class),
                    new AuthenticatedIdentityService(),
                    $container->get(\Gica\Cqrs\FutureEventsStore::class),
                    new EventsApplierOnAggregate
                );
            },
        ],
        'abstract_factories' => [
            \Infrastructure\Implementations\AbstractFactory::class,
        ],
    ],
];
