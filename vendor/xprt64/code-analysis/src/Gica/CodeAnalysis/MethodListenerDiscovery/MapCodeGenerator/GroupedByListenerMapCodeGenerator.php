<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\CodeAnalysis\MethodListenerDiscovery\MapCodeGenerator;


use Gica\CodeAnalysis\MethodListenerDiscovery\ListenerMethod;
use Gica\CodeAnalysis\MethodListenerDiscovery\MapGrouper\GrouperByListener;

class GroupedByListenerMapCodeGenerator extends MapCodeGeneratorBase
{
    protected function getLevel1FirstItem(ListenerMethod $listener): string
    {
        return $listener->getEventClassName();
    }

    protected function getLevel1SecondItem(ListenerMethod $listener): string
    {
        return $listener->getMethodName();
    }

    protected function group(array $map)
    {
        return (new GrouperByListener())->groupMap($map);
    }

}