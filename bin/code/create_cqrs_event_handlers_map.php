<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

use Gica\Cqrs\CodeGeneration\ReadModelEventListenersMapCodeGenerator;

require_once dirname(__FILE__) . "/../bin_includes.php";

global $container;

$classInfo = new \ReflectionClass(\Domain\Cqrs\EventSubscriberTemplate::class);

$domainDirectory = dirname(dirname($classInfo->getFileName()));

$readModelEventListenersMapCodeGenerator = $container->get(ReadModelEventListenersMapCodeGenerator::class);

$readModelEventListenersMapCodeGenerator->generate(
    new \Bin\Logger(),
    null,
    \Domain\Cqrs\EventSubscriberTemplate::class,
    $domainDirectory . '/Read',
    $domainDirectory . '/Cqrs/EventSubscriber.php',
    'EventSubscriber'
);
