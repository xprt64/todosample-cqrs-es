<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

use Api\ApiDirectory;
use Bin\Logger;
use Domain\DomainDirectory;
use Dudulina\CodeGeneration\CommandValidatorsMapCodeGenerator;
use Gica\FileSystem\OperatingSystemFileSystem;
use Infrastructure\InfrastructureDirectory;

require_once __DIR__ . '/../bin_includes.php';

global $container;

$classInfo = new \ReflectionClass(\Infrastructure\Cqrs\CommandValidatorSubscriberTemplate::class);

$commandValidatorsMapCodeGenerator = new CommandValidatorsMapCodeGenerator(
    new Logger(),
    new OperatingSystemFileSystem()
);

$files = new \AppendIterator();

$files->append(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(DomainDirectory::getDomainDirectory() . '/Write')));


$commandValidatorsMapCodeGenerator->generate(
    \Infrastructure\Cqrs\CommandValidatorSubscriberTemplate::class,
    $files,
    InfrastructureDirectory::getInfrastructureDirectory() . '/Cqrs/CommandValidatorSubscriber.php',
    'CommandValidatorSubscriber'
);
