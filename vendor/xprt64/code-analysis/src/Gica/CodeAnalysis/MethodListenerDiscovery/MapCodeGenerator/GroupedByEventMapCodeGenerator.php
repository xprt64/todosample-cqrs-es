<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\CodeAnalysis\MethodListenerDiscovery\MapCodeGenerator;


use Gica\CodeAnalysis\MethodListenerDiscovery\ListenerMethod;
use Gica\CodeAnalysis\MethodListenerDiscovery\MapGrouper\GrouperByEvent;

class GroupedByEventMapCodeGenerator extends MapCodeGeneratorBase
{
    protected function getLevel1FirstItem(ListenerMethod $listener):string
    {
        return $listener->getClassName();
    }

    protected function getLevel1SecondItem(ListenerMethod $listener):string
    {
        return $listener->getMethodName();
    }

    protected function group(array $map)
    {
        return (new GrouperByEvent())->groupMap($map);
    }
}