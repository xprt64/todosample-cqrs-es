<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

use Gica\Cqrs\CodeGeneration\ReadModelsMapCodeGenerator;

require_once dirname(__FILE__) . "/../bin_includes.php";

global $container;

$classInfo = new \ReflectionClass(\Domain\Cqrs\ReadModelMapTemplate::class);

$domainDirectory = dirname(dirname($classInfo->getFileName()));

$readModelsMapCodeGenerator = $container->get(ReadModelsMapCodeGenerator::class);

$readModelsMapCodeGenerator->generate(
    new \Bin\Logger(),
    null,
    \Domain\Cqrs\ReadModelMapTemplate::class,
    $domainDirectory . '/Read',
    $domainDirectory . '/Cqrs/ReadModelMap.php',
    'ReadModelMap'
);
