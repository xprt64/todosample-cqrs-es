<?php


namespace Gica\CodeAnalysis\MethodListenerDiscovery\MapGrouper;


use Gica\CodeAnalysis\MethodListenerDiscovery\ListenerMethod;
use Gica\CodeAnalysis\Shared\ClassSorter\ByConstructorDependencySorter;

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
    private function sortListeners($listeners)
    {
        $classSorter = new ByConstructorDependencySorter();

        usort($listeners, function (ListenerMethod $a, ListenerMethod $b) use ($classSorter) {
            return $classSorter->__invoke($a->getClass(), $b->getClass());
        });

        return $listeners;
    }

}