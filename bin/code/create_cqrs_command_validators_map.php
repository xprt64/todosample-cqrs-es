<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

use Domain\DomainDirectory;
use Gica\Cqrs\CodeGeneration\CommandValidatorsMapCodeGenerator;
use Gica\FileSystem\OperatingSystemFileSystem;

require_once dirname(__FILE__) . "/../bin_includes.php";

global $container;

$classInfo = new \ReflectionClass(\Infrastructure\Cqrs\CommandValidatorSubscriberTemplate::class);

$commandValidatorsMapCodeGenerator = new CommandValidatorsMapCodeGenerator(
    new \Bin\Logger(),
    new OperatingSystemFileSystem()
);

$commandValidatorsMapCodeGenerator->generate(
    \Infrastructure\Cqrs\CommandValidatorSubscriberTemplate::class,
    DomainDirectory::getDomainDirectory() . '/Write',
    \Infrastructure\Cqrs\Directory::getDirectory() . '/CommandValidatorSubscriber.php',
    'CommandValidatorSubscriber'
);
