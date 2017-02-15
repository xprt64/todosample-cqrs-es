<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

use Bin\Logger;
use Domain\Cqrs\EventSubscriberTemplate;
use Gica\Cqrs\CodeGeneration\SagaEventListenerMapCodeGenerator;

require_once dirname(__FILE__) . "/../bin_includes.php";

$classInfo = new \ReflectionClass(EventSubscriberTemplate::class);

$domainDirectory = dirname(dirname($classInfo->getFileName()));

global $container;

$sagaEventListenerMapCodeGenerator = $container->get(SagaEventListenerMapCodeGenerator::class);

$sagaEventListenerMapCodeGenerator->generate(
    new Logger(),
    null,
    EventSubscriberTemplate::class,
    dirname(dirname($classInfo->getFileName())),
    $domainDirectory . '/Cqrs/WriteSideEventSubscriber.php',
    'WriteSideEventSubscriber'
);
