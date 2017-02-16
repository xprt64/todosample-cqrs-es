<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

use Bin\Logger;
use Domain\Cqrs\EventSubscriberTemplate;
use Gica\Cqrs\CodeGeneration\SagaEventListenerMapCodeGenerator;
use Gica\FileSystem\OperatingSystemFileSystem;

require_once dirname(__FILE__) . "/../bin_includes.php";

$classInfo = new \ReflectionClass(EventSubscriberTemplate::class);

$domainDirectory = dirname(dirname($classInfo->getFileName()));

global $container;

$sagaEventListenerMapCodeGenerator = new SagaEventListenerMapCodeGenerator(
    new \Bin\Logger(),
    new OperatingSystemFileSystem()
);

$sagaEventListenerMapCodeGenerator->generate(
    EventSubscriberTemplate::class,
    dirname(dirname($classInfo->getFileName())),
    $domainDirectory . '/Cqrs/WriteSideEventSubscriber.php',
    'WriteSideEventSubscriber'
);
