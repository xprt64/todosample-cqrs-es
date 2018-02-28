<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

use Bin\Logger;
use Domain\DomainDirectory;
use Dudulina\CodeGeneration\SagaEventListenerMapCodeGenerator;
use Gica\FileSystem\OperatingSystemFileSystem;
use Infrastructure\InfrastructureDirectory;

require_once __DIR__ . '/../bin_includes.php';

$classInfo = new \ReflectionClass(\Infrastructure\Cqrs\EventSubscriberTemplate::class);

global $container;

$sagaEventListenerMapCodeGenerator = new SagaEventListenerMapCodeGenerator(
    new Logger(),
    new OperatingSystemFileSystem()
);

$sagaEventListenerMapCodeGenerator->generate(
    \Infrastructure\Cqrs\EventSubscriberTemplate::class,
    new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(DomainDirectory::getDomainDirectory())),
    InfrastructureDirectory::getInfrastructureDirectory() . '/Cqrs/SagaEventSubscriber.php',
    'SagaEventSubscriber'
);
