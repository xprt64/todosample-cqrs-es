# Todo sample application for cqrs-es for PHP7 #
This is a sample application to show how [cqrs-es](https://github.com/xprt64/cqrs-es "cqrs-es on github") can be used in production.

## Architecture ##

The application uses a [zend-expressive](https://zendframework.github.io/zend-expressive/ "https://zendframework.github.io/zend-expressive/") skeleton for HTTP bindings, dependency injection and template rendering.

The architecture is based on CQRS and Event Sourcing using [xprt64/cqrs-es](https://github.com/xprt64/cqrs-es "cqrs-es on github") library.

The basic ideea of CQRS with Event Sourcing is that in order to modify the state of the application commands must be executed.
The result of the command are the events that are persisted in an Event Store.
Those events are used to rehydrate the write models and to update the read models (the projections).
Read more general [documentation about this implementation of CQRS here](https://github.com/xprt64/cqrs-es/blob/master/DOCUMENTATION.md).

## Test it ##
To run this application you must clone this repository the use `docker-compose` to start it.

```
git clone https://github.com/xprt64/todosample-cqrs-es todosample-cqrs-es
cd todosample-cqrs-es
docker-compose up --build
```
Then, in your browser, access [http://localhost](http://localhost).

