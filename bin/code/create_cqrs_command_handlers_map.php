<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

use Domain\Cqrs\CommandSubscriberTemplate;
use Gica\Cqrs\CodeGeneration\CommandHandlersMapCodeGenerator;
use Gica\FileSystem\OperatingSystemFileSystem;

require_once dirname(__FILE__) . "/../bin_includes.php";

global $container;

$classInfo = new \ReflectionClass(CommandSubscriberTemplate::class);

$domainPath = dirname(dirname($classInfo->getFileName()));

$outputPath = dirname($classInfo->getFileName()) . '/CommandHandlerSubscriber.php';

$commandHandlersMapGenerator = new CommandHandlersMapCodeGenerator(
    new \Bin\Logger(),
    new OperatingSystemFileSystem()
);

$commandHandlersMapGenerator->generate(
    CommandSubscriberTemplate::class,
    $domainPath,
    $outputPath,
    'CommandHandlerSubscriber'
);
