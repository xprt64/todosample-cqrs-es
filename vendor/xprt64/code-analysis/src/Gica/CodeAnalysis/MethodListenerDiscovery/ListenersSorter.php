<?php


namespace Gica\CodeAnalysis\MethodListenerDiscovery;


use Gica\CodeAnalysis\Shared\ClassSorter;

class ListenersSorter
{

    /**
     * @var ClassSorter
     */
    private $classSorter;

    public function __construct(ClassSorter $classSorter)
    {
        $this->classSorter = $classSorter;
    }

    /**
     * @param ListenerMethod[] $listeners
     * @return ListenerMethod[]
     */
    public function sortListeners($listeners)
    {
        $sortedClasses = $this->classSorter->sortClasses(array_map(function (ListenerMethod $listenerMethod) {
            return $listenerMethod->getClass();
        }, $listeners));

        $sortedClassesNames = array_map(function (\ReflectionClass $class) {
            return $class->name;
        }, $sortedClasses);

        usort($listeners, function (ListenerMethod $a, ListenerMethod $b) use ($sortedClassesNames) {
            return array_search($a->getClassName(), $sortedClassesNames) <=> array_search($b->getClassName(), $sortedClassesNames);
        });

        return $listeners;
    }
}