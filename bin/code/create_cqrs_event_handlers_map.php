<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

use Bin\Logger;
use Domain\DomainDirectory;
use Dudulina\CodeGeneration\ReadModelEventListenersMapCodeGenerator;
use Gica\FileSystem\OperatingSystemFileSystem;
use Infrastructure\InfrastructureDirectory;

require_once __DIR__ . '/../bin_includes.php';

global $container;

$classInfo = new \ReflectionClass(\Infrastructure\Cqrs\EventSubscriberTemplate::class);

$readModelEventListenersMapCodeGenerator = new ReadModelEventListenersMapCodeGenerator(
    new Logger(),
    new OperatingSystemFileSystem()
);

$readModelEventListenersMapCodeGenerator->generate(
    \Infrastructure\Cqrs\EventSubscriberTemplate::class,
    new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(DomainDirectory::getDomainDirectory() . '')),
    InfrastructureDirectory::getInfrastructureDirectory() . '/Cqrs/EventSubscriber.php',
    'EventSubscriber'
);
