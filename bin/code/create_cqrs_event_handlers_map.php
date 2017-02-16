<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

use Gica\Cqrs\CodeGeneration\ReadModelEventListenersMapCodeGenerator;
use Gica\FileSystem\OperatingSystemFileSystem;

require_once dirname(__FILE__) . "/../bin_includes.php";

global $container;

$classInfo = new \ReflectionClass(\Domain\Cqrs\EventSubscriberTemplate::class);

$domainDirectory = dirname(dirname($classInfo->getFileName()));

$readModelEventListenersMapCodeGenerator = new ReadModelEventListenersMapCodeGenerator(
    new \Bin\Logger(),
    new OperatingSystemFileSystem()
);

$readModelEventListenersMapCodeGenerator->generate(
    \Domain\Cqrs\EventSubscriberTemplate::class,
    $domainDirectory . '/Read',
    $domainDirectory . '/Cqrs/EventSubscriber.php',
    'EventSubscriber'
);
