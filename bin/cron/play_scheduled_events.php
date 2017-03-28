<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace bin\cron;
global $container;

use Bin\EnsureSingletonScript;
use Gica\Cqrs\Scheduling\ScheduledEventsPlayer;

require_once dirname(__FILE__) . "/../bin_includes.php";

EnsureSingletonScript::lock(__FILE__);

/** @var ScheduledEventsPlayer $eventsPlayer */
$eventsPlayer = $container->get(ScheduledEventsPlayer::class);

echo "waiting for events...\n";

while (true) {
    $eventsPlayer->run();
    sleep(1);
}

die("\n");