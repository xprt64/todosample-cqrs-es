<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

use Bin\Logger;
use Domain\Cqrs\CommandSubscriberTemplate;
use Gica\Cqrs\CodeGeneration\AggregateEventApplyHandlerValidator;

require_once dirname(__FILE__) . "/../bin_includes.php";

global $container;

$classInfo = new \ReflectionClass(CommandSubscriberTemplate::class);

$domainDirectory = dirname(dirname($classInfo->getFileName()));

$aggregateEventHandlerValidator = $container->get(AggregateEventApplyHandlerValidator::class);

$aggregateEventHandlerValidator->validate(
    new Logger(),
    $domainDirectory . '/Write'
);