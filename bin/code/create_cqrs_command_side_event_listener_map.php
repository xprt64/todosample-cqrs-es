<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

use Bin\Logger;
use Infrastructure\Cqrs\EventSubscriberTemplate;
use Domain\DomainDirectory;
use Gica\Cqrs\CodeGeneration\SagaEventListenerMapCodeGenerator;
use Gica\FileSystem\OperatingSystemFileSystem;

require_once dirname(__FILE__) . "/../bin_includes.php";

$classInfo = new \ReflectionClass(EventSubscriberTemplate::class);

global $container;

$sagaEventListenerMapCodeGenerator = new SagaEventListenerMapCodeGenerator(
    new \Bin\Logger(),
    new OperatingSystemFileSystem()
);

$sagaEventListenerMapCodeGenerator->generate(
    EventSubscriberTemplate::class,
    dirname(dirname($classInfo->getFileName())),
    \Infrastructure\Cqrs\Directory::getDirectory() . '/WriteSideEventSubscriber.php',
    'WriteSideEventSubscriber'
);
