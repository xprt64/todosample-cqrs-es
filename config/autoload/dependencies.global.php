<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

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
            Helper\ServerUrlHelper::class               => Helper\ServerUrlHelper::class,
            \Gica\FileSystem\FileSystemInterface::class => \Gica\FileSystem\OperatingSystemFileSystem::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories'          => [
            Application::class                                                  => ApplicationFactory::class,
            Helper\UrlHelper::class                                             => Helper\UrlHelperFactory::class,
            LoggerInterface::class                                              => function () {
                return null;
            },
            \Gica\Dependency\AbstractFactory::class                             => function (\Interop\Container\ContainerInterface $container) {
                return new \Gica\Dependency\ConstructorAbstractFactory($container);
            },
            \Domain\Write\Todo\DuplicateTodoDetection\ListOfAllTodoNames::class => function (\Interop\Container\ContainerInterface $container) {
                return $container->get(\Infrastructure\Orm\ListOfAllTodoNamesMongo::class);
            },

        ],
        'abstract_factories' => [
            \Infrastructure\Implementations\AbstractFactory::class,
        ],
    ],
];
