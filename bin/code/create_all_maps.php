<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

/**
 * Run this after you add a new command or event to update the subscribers
 */
$dir = __DIR__;

system("php -f $dir/validate_cqrs_aggregate_event_handlers.php");
system("php -f $dir/create_cqrs_command_handlers_map.php");
system("php -f $dir/create_cqrs_command_validators_map.php");
system("php -f $dir/create_cqrs_event_handlers_map.php");
system("php -f $dir/create_cqrs_command_side_event_listener_map.php");
system("php -f $dir/create_cqrs_read_model_map.php");
