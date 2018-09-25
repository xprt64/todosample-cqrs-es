<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

/**
 * Run this after you add a new command or event to update the subscribers
 */
$dir = __DIR__ . '/../..';

system("php -f $dir/vendor/xprt64/dudulina/bin/create_bindings.php -- --src=\"$dir/src\" > $dir/deploy/cqrs_bindings.php");
