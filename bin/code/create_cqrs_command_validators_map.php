<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

use Gica\Cqrs\CodeGeneration\CommandValidatorsMapCodeGenerator;

require_once dirname(__FILE__) . "/../bin_includes.php";

global $container;

$classInfo = new \ReflectionClass(\Domain\Cqrs\CommandValidatorSubscriberTemplate::class);

$domainDirectory = dirname(dirname($classInfo->getFileName()));

$commandValidatorsMapCodeGenerator = $container->get(CommandValidatorsMapCodeGenerator::class);

$commandValidatorsMapCodeGenerator->generate(
    new \Bin\Logger(),
    null,
    \Domain\Cqrs\CommandValidatorSubscriberTemplate::class,
    $domainDirectory . '/Write',
    $domainDirectory . '/Cqrs/CommandValidatorSubscriber.php',
    'CommandValidatorSubscriber'
);
