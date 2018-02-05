<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

use Infrastructure\Cqrs\CommandSubscriberTemplate;
use Domain\DomainDirectory;
use Dudulina\CodeGeneration\CommandHandlersMapCodeGenerator;
use Gica\FileSystem\OperatingSystemFileSystem;

require_once dirname(__FILE__) . "/../bin_includes.php";

global $container;

$classInfo = new \ReflectionClass(CommandSubscriberTemplate::class);

$outputPath = \Infrastructure\Cqrs\Directory::getDirectory() . '/CommandHandlerSubscriber.php';

$commandHandlersMapGenerator = new CommandHandlersMapCodeGenerator(
    new \Bin\Logger(),
    new OperatingSystemFileSystem()
);

$commandHandlersMapGenerator->generate(
    CommandSubscriberTemplate::class,
    DomainDirectory::getDomainDirectory(),
    $outputPath,
    'CommandHandlerSubscriber'
);
