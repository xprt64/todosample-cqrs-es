<?php

return [
    'debug' => false,

    'config_cache_enabled' => false,

    'zend-expressive' => [
        'error_handler' => [
            'template_404'   => 'error::404',
            'template_error' => 'error::error',
        ],
    ],
    'mongoEventStore'        => [
        'dsn'      => 'mongodb://eventstore:27017/',
        'database' => 'eventStore',
    ],
    'mongoReadModels'        => [
        'dsn'      => 'mongodb://readmodelsdb:27017/',
        'database' => 'readModels',
    ],

];
