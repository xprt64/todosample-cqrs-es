<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

use Bin\Logger;
use Domain\DomainDirectory;
use Dudulina\CodeGeneration\ReadModelsMapCodeGenerator;
use Gica\FileSystem\OperatingSystemFileSystem;
use Infrastructure\InfrastructureDirectory;

require_once __DIR__ . '/../bin_includes.php';

global $container;

$classInfo = new \ReflectionClass(\Infrastructure\Cqrs\ReadModelMapTemplate::class);

$readModelsMapCodeGenerator = new ReadModelsMapCodeGenerator(
    new Logger(),
    new OperatingSystemFileSystem()
);

$readModelsMapCodeGenerator->generate(
    \Infrastructure\Cqrs\ReadModelMapTemplate::class,
    new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(DomainDirectory::getDomainDirectory())),
    InfrastructureDirectory::getInfrastructureDirectory() . '/Cqrs/ReadModelMap.php',
    'ReadModelMap'
);
