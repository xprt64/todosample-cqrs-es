<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

use Bin\Logger;
use Domain\DomainDirectory;
use Dudulina\CodeGeneration\CommandHandlersMapCodeGenerator;
use Gica\FileSystem\OperatingSystemFileSystem;
use Infrastructure\InfrastructureDirectory;
use Psr\Container\ContainerInterface;

require_once __DIR__ . '/../bin_includes.php';

/** @var ContainerInterface $container */
global $container;

$classInfo = new \ReflectionClass(\Infrastructure\Cqrs\CommandSubscriberTemplate::class);

$outputPath = InfrastructureDirectory::getInfrastructureDirectory() . '/Cqrs/CommandHandlerSubscriber.php';

$commandHandlersMapGenerator = new CommandHandlersMapCodeGenerator(
    new Logger(),
    new OperatingSystemFileSystem()
);

$commandHandlersMapGenerator->generate(
    \Infrastructure\Cqrs\CommandSubscriberTemplate::class,
    new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(DomainDirectory::getDomainDirectory() . '/Write')),
    $outputPath,
    'CommandHandlerSubscriber'
);
