# Todo sample application for cqrs-es for PHP7 #
This is a sample application to show how [cqrs-es](https://github.com/xprt64/cqrs-es "cqrs-es on github") can be used in production.

## Architecture ##

The application uses a [zend-expressive](https://zendframework.github.io/zend-expressive/ "https://zendframework.github.io/zend-expressive/") skeleton for HTTP bindings, dependency injection and template rendering.

The architecture is based on CQRS and Event Sourcing using [xprt64/cqrs-es](https://github.com/xprt64/cqrs-es "cqrs-es on github") library.

The basic ideea of CQRS with Event Sourcing is that in order to modify the state of the application commands must be executed.
The result of the command are the events that are persisted in an Event Store.
Those events are used to rehydrate the write models and to update the read models (the projections).
Read more general [documentation about this implementation of CQRS here](https://github.com/xprt64/cqrs-es/blob/master/DOCUMENTATION.md).

## Configuration ##
The application uses a dependency injection container (`\Zend\ServiceManager\ServiceManager`).
The file `config/autoload/dependencies.global.php` contains the composition root.
Here we must wire the dependencies to the CQRS library.

### The abstract factory ###

An abstract factory is used by the application to create Read Models, Sagas and Command validators.
```php
\Gica\Dependency\AbstractFactory::class                      => function (\Interop\Container\ContainerInterface $container) {
    return new \Gica\Dependency\ConstructorAbstractFactory($container);
},
```

### The databases ###

```php
\Domain\Dependency\Database\EventStoreDatabase::class => function (ContainerInterface $container) {
    return $container->get(\Infrastructure\Implementations\EventStoreDatabase::class);
},
\Domain\Dependency\Database\ReadModelsDatabase::class => function (ContainerInterface $container) {
    return $container->get(\Infrastructure\Implementations\ReadModelsDatabase::class);
},
```

These database interfaces are used to invert the dependency from the Domain to Infrastructure.

### CQRS specific configuration ###

#### Event store ####
```php
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
```

#### Event subscribers and command subscribers ####

```php
\Gica\Cqrs\Command\CommandSubscriber::class => function (ContainerInterface $container) {
    return $container->get(\Domain\Cqrs\CommandHandlerSubscriber::class);
},

\Gica\Cqrs\Event\EventSubscriber::class => function (ContainerInterface $container) {
    return $container->get(\Domain\Cqrs\EventSubscriber::class);
},

CommandValidatorSubscriber::class => function (ContainerInterface $container) {
    return $container->get(\Domain\Cqrs\CommandValidatorSubscriber::class);
},
```

#### Command dispatcher ####

```php
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
```

## Automation tools ##
In order to speed up development, some tools exists in the `bin/code` directory.
These tools parse the source code in the Domain directory and build the folowing maps:
- command handlers map: `create_cqrs_command_handlers_map.php`
- command validators map: `create_cqrs_command_validators_map.php`
- event handlers map: `create_cqrs_event_handlers_map.php`
- read models list map: `create_cqrs_read_model_map.php`
- saga event processors map: `create_cqrs_command_side_event_listener_map.php`

You should run them all after any relevant modification of the source code by:
```
php -f bin/code/create_all_maps.php
```

A relevant modification is in any of the following cases:
- a new command handler
- a new event applier on the Aggregate is created
- a new event handler is created
- an existing command is renamed, moved
- an existing event is renamed, moved

## Testing ##
What is more important than testing? Nothing!

You can create unit-tests for the Aggregates using `BddAggregateTestHelper`.
Here is a sample unit-test:
```php

class TodoAggregateTest extends PHPUnit_Framework_TestCase
{

    public function test_handleAddNewTodo()
    {
        $command  = new AddNewTodo(
            123, 'test'
        );

        $expectedEvent = new ANewTodoWasAdded('test');

        $sut = new TodoAggregate();

        $helper = new BddAggregateTestHelper(
            new CommandHandlerSubscriber()
        );

        $helper->onAggregate($sut);
        $helper->given();
        $helper->when($command);
        $helper->then($expectedEvent);

        $this->assertTrue(true);//fake assertion
    }

    public function test_handleAddNewTodo_idempotent()
    {
        $command  = new AddNewTodo(
            123, 'test'
        );

        $priorEvent = new ANewTodoWasAdded('test');

        $sut = new TodoAggregate();

        $helper = new BddAggregateTestHelper(
            new CommandHandlerSubscriber()
        );

        $helper->onAggregate($sut);
        $helper->given($priorEvent);
        $helper->when($command);
        $helper->then();//no events must be yielded

        $this->assertTrue(true);//fake assertion
    }
}
```

## Run it ##

To run this application you must clone this repository then use `docker-compose up` to start it.

```
git clone https://github.com/xprt64/todosample-cqrs-es todosample-cqrs-es
cd todosample-cqrs-es
docker-compose up --build
```

Then, in your browser, access [http://localhost](http://localhost).

