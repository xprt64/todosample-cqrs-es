<?php


namespace Gica\CodeAnalysis\MethodListenerDiscovery\MapGrouper;


use Gica\CodeAnalysis\MethodListenerDiscovery\ListenerMethod;

class GrouperByListener
{
    /**
     * @param ListenerMethod[] $map
     * @return array array of ListenerMethod[]
     */
    public function groupMap(array $map)
    {
        $result = [];

        foreach ($map as $listenerMethod) {
            $result[$listenerMethod->getClassName()][] = $listenerMethod;
        }

        return $result;
    }
}