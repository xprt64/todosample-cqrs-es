<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

use Domain\Cqrs\CommandSubscriberTemplate;
use Gica\Cqrs\CodeGeneration\CommandHandlersMapCodeGenerator;

require_once dirname(__FILE__) . "/../bin_includes.php";

global $container;

$classInfo = new \ReflectionClass(CommandSubscriberTemplate::class);

$domainPath = dirname(dirname($classInfo->getFileName()));

$outputPath = dirname($classInfo->getFileName()) . '/CommandHandlerSubscriber.php';

/** @var CommandHandlersMapCodeGenerator $commandHandlersMapGenerator */
$commandHandlersMapGenerator = $container->get(CommandHandlersMapCodeGenerator::class);

$commandHandlersMapGenerator->generate(
    new \Bin\Logger(),
    null,
    CommandSubscriberTemplate::class,
    $domainPath,
    $outputPath,
    'CommandHandlerSubscriber'
);
