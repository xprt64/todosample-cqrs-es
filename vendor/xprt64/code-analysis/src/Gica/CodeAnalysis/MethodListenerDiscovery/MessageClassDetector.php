<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\CodeAnalysis\MethodListenerDiscovery;


interface MessageClassDetector
{
    public function isMessageClass(\ReflectionClass $typeHintedClass):bool;

    public function isMethodAccepted(\ReflectionMethod  $reflectionMethod):bool;
}