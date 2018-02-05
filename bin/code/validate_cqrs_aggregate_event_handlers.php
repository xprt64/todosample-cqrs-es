<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

use Infrastructure\Cqrs\CommandSubscriberTemplate;
use Domain\DomainDirectory;
use Dudulina\CodeGeneration\AggregateEventApplyHandlerValidator;
use Gica\FileSystem\OperatingSystemFileSystem;

require_once dirname(__FILE__) . "/../bin_includes.php";

global $container;

$classInfo = new \ReflectionClass(CommandSubscriberTemplate::class);

$aggregateEventHandlerValidator = new AggregateEventApplyHandlerValidator(
    new \Bin\Logger(),
    new OperatingSystemFileSystem()
);

$aggregateEventHandlerValidator->validate(
    DomainDirectory::getDomainDirectory() . '/Write'
);