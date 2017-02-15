<?php

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\AuraRouter::class,
            UI\Action\PingAction::class => UI\Action\PingAction::class,
        ],
        'factories' => [
        ],
    ],

    'routes' => [
        [
            'name' => 'home',
            'path' => '/',
            'middleware' => UI\Action\HomePageAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'api.ping',
            'path' => '/api/ping',
            'middleware' => UI\Action\PingAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'todos.add',
            'path' => '/todos/add',
            'middleware' => UI\Action\AddTodoAction::class,
            'allowed_methods' => ['POST'],
        ],
        [
            'name' => 'todo.markAsDone',
            'path' => '/todo/{id}/markAsDone',
            'middleware' => UI\Action\MarkTodoAsDoneAction::class,
            'allowed_methods' => ['POST'],
        ],
        [
            'name' => 'todo.unmarkAsDone',
            'path' => '/todo/{id}/unmarkAsDone',
            'middleware' => UI\Action\UnmarkTodoAsDoneAction::class,
            'allowed_methods' => ['POST'],
        ],
       [
            'name' => 'todo.delete',
            'path' => '/todo/{id}/delete',
            'middleware' => UI\Action\DeleteTodoAction::class,
            'allowed_methods' => ['POST'],
        ],
    ],
];
