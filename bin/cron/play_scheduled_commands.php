<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace bin\cron;
global $container;

use Bin\EnsureSingletonScript;
use Gica\Cqrs\Scheduling\ScheduledCommandsDispatcher;

require_once dirname(__FILE__) . "/../bin_includes.php";

EnsureSingletonScript::lock(__FILE__);

/** @var ScheduledCommandsDispatcher $scheduledCommandDispatcher */
$scheduledCommandDispatcher = $container->get(ScheduledCommandsDispatcher::class);

echo "waiting for commands...\n";

while (true) {
    $scheduledCommandDispatcher->run();
    sleep(1);
}

die("\n");