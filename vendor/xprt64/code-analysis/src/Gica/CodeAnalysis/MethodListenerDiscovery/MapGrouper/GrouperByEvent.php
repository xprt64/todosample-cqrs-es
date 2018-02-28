<?php


namespace Gica\CodeAnalysis\MethodListenerDiscovery\MapGrouper;


use Gica\CodeAnalysis\MethodListenerDiscovery\ListenerMethod;
use Gica\CodeAnalysis\MethodListenerDiscovery\ListenersSorter;
use Gica\CodeAnalysis\Shared\ClassSorter\TopologySorter;

class GrouperByEvent
{

    /**
     * @param ListenerMethod[] $map
     * @return array
     */
    public function groupMap(array $map)
    {
        $groupedByEvent = [];

        foreach ($map as $listenerMethod) {
            $groupedByEvent[$listenerMethod->getEventClassName()][] = $listenerMethod;
        }

        $sorted = [];

        foreach ($groupedByEvent as $eventClass => $listeners) {
            $sorted[$eventClass] = $this->sortListeners($listeners);
        }

        return $sorted;
    }

    /**
     * @param ListenerMethod[] $listeners
     * @return ListenerMethod[]
     */
    public function sortListeners($listeners)
    {
        return (new ListenersSorter(new TopologySorter))->sortListeners($listeners);
    }
}