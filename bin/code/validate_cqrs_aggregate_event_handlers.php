<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

use Domain\Cqrs\CommandSubscriberTemplate;
use Gica\Cqrs\CodeGeneration\AggregateEventApplyHandlerValidator;
use Gica\FileSystem\OperatingSystemFileSystem;

require_once dirname(__FILE__) . "/../bin_includes.php";

global $container;

$classInfo = new \ReflectionClass(CommandSubscriberTemplate::class);

$domainDirectory = dirname(dirname($classInfo->getFileName()));

$aggregateEventHandlerValidator = new AggregateEventApplyHandlerValidator(
    new \Bin\Logger(),
    new OperatingSystemFileSystem()
);

$aggregateEventHandlerValidator->validate(
    $domainDirectory . '/Write'
);