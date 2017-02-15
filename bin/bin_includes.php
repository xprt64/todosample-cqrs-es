<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

set_exception_handler(function (Throwable $e) {
    $s = "Exception:'{$e->getMessage()}' on file {$e->getFile()}:{$e->getLine()}";
    echo "\n$s\n{$e->getTraceAsString()}\n";

    trigger_error($s, E_USER_WARNING);
    exit(1);
});

global $container;

chdir(dirname(__DIR__));
require __DIR__ . '/../vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require __DIR__ . '/../config/container.php';

$_GET = array_merge((array)$_GET, (new \Gica\Lib\Cli())->parseArgv($GLOBALS['argv']));

while (ob_get_length() > 0 && ob_end_clean()) ;


function displayScriptHeader($text)
{
    pecho(<<<TXT
#
# <cyan>$text</cyan>
#
"
TXT
    );
}

if (!function_exists('pecho')) {
    function pecho($s, $return = 0)
    {
        return print_r($s, $return);
    }
}

function dieOk($text = 'DONE')
{
    pecho("<green>== $text ==</green>\n");
}

function confirm($text, $param = null)
{
    echo $text;

    flush();

    if(($param && $_GET[$param] === 'y') || fgetc(STDIN) == 'y')
    {
        echo "\nconfirmation received.\n";
        return;
    }

    die("\nexit requested by you.\n");
}

function get($param)
{
    return $_GET[$param];
}