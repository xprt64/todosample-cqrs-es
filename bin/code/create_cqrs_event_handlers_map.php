<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

use Domain\DomainDirectory;
use Gica\Cqrs\CodeGeneration\ReadModelEventListenersMapCodeGenerator;
use Gica\FileSystem\OperatingSystemFileSystem;

require_once dirname(__FILE__) . "/../bin_includes.php";

global $container;

$classInfo = new \ReflectionClass(\Infrastructure\Cqrs\EventSubscriberTemplate::class);

$readModelEventListenersMapCodeGenerator = new ReadModelEventListenersMapCodeGenerator(
    new \Bin\Logger(),
    new OperatingSystemFileSystem()
);

$readModelEventListenersMapCodeGenerator->generate(
    \Infrastructure\Cqrs\EventSubscriberTemplate::class,
    DomainDirectory::getDomainDirectory() . '/Read',
    \Infrastructure\Cqrs\Directory::getDirectory() . '/EventSubscriber.php',
    'EventSubscriber'
);
