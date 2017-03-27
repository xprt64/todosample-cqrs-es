<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\CodeAnalysis\MethodListenerDiscovery;


interface ListenerClassValidator
{
    public function isClassAccepted(\ReflectionClass $typeHintedClass):bool;
}