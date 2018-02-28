<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

use Bin\Logger;
use Domain\DomainDirectory;
use Dudulina\CodeGeneration\AggregateEventApplyHandlerValidator;

require_once __DIR__ . '/../bin_includes.php';

global $container;

$classInfo = new \ReflectionClass(\Infrastructure\Cqrs\CommandSubscriberTemplate::class);

$aggregateEventHandlerValidator = new AggregateEventApplyHandlerValidator(
    new Logger()
);

$aggregateEventHandlerValidator->validate(
    new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(DomainDirectory::getDomainDirectory() . '/Write'))
);