<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

use Domain\DomainDirectory;
use Gica\Cqrs\CodeGeneration\ReadModelsMapCodeGenerator;
use Gica\FileSystem\OperatingSystemFileSystem;

require_once dirname(__FILE__) . "/../bin_includes.php";

global $container;

$classInfo = new \ReflectionClass(\Infrastructure\Cqrs\ReadModelMapTemplate::class);

$readModelsMapCodeGenerator = new ReadModelsMapCodeGenerator(
    new \Bin\Logger(),
    new OperatingSystemFileSystem()
);

$readModelsMapCodeGenerator->generate(
    \Infrastructure\Cqrs\ReadModelMapTemplate::class,
    DomainDirectory::getDomainDirectory() . '/Read',
    \Infrastructure\Cqrs\Directory::getDirectory() . '/ReadModelMap.php',
    'ReadModelMap'
);
