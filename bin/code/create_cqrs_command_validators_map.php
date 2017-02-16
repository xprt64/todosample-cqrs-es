<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

use Gica\Cqrs\CodeGeneration\CommandValidatorsMapCodeGenerator;
use Gica\FileSystem\OperatingSystemFileSystem;

require_once dirname(__FILE__) . "/../bin_includes.php";

global $container;

$classInfo = new \ReflectionClass(\Domain\Cqrs\CommandValidatorSubscriberTemplate::class);

$domainDirectory = dirname(dirname($classInfo->getFileName()));

$commandValidatorsMapCodeGenerator = new CommandValidatorsMapCodeGenerator(
    new \Bin\Logger(),
    new OperatingSystemFileSystem()
);

$commandValidatorsMapCodeGenerator->generate(
    \Domain\Cqrs\CommandValidatorSubscriberTemplate::class,
    $domainDirectory . '/Write',
    $domainDirectory . '/Cqrs/CommandValidatorSubscriber.php',
    'CommandValidatorSubscriber'
);
