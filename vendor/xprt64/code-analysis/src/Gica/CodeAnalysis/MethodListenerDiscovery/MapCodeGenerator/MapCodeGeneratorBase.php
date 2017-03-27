<?php


namespace Gica\CodeAnalysis\MethodListenerDiscovery\MapCodeGenerator;


use Gica\CodeAnalysis\MethodListenerDiscovery\ListenerMethod;
use Gica\CodeAnalysis\MethodListenerDiscovery\MapCodeGenerator;

abstract class MapCodeGeneratorBase implements MapCodeGenerator
{
    const SPACES_AT_ROOT = 8;
    const SPACES_AT_MESSAGES = 12;
    const SPACES_AT_HANDLERS = 16;

    /**
     * @inheritdoc
     */
    public function generateAndGetFileContents(array $map, $template)
    {
        $mapString = $this->getMapAsString($this->group($map));

        return str_replace('[/*do not modify this line!*/]', "[\n" . $mapString . "\n" . $this->spaces(self::SPACES_AT_ROOT) . ']', $template);
    }

    /**
     * @param array $map
     * @return string
     */
    private function getMapAsString(array $map)
    {
        $eventEntries = [];

        foreach ($map as $key => $listeners) {
            $eventItem = $this->generateLevel0Item($key, $listeners);

            $eventEntries[] = $eventItem;
        }

        return implode("\n\n", $eventEntries);
    }

    protected function generateLevel0Item($eventClass, array $listeners)
    {
        return $this->spaces(self::SPACES_AT_MESSAGES) . $this->prependSlash($eventClass) . "::class => [\n" .
            implode("\n", $this->generateLevel1Item($listeners)) . "\n" .
            $this->spaces(self::SPACES_AT_MESSAGES) . "],";
    }

    protected function generateLevel1Item(array $eventListenerMethods)
    {
        return array_map(function (ListenerMethod $listener) {
            return $this->spaces(self::SPACES_AT_HANDLERS) .
                '[' . $this->prependSlash($this->getLevel1FirstItem($listener)) . '::class' . ', \'' . $this->getLevel1SecondItem($listener) . '\'],';
        }, $eventListenerMethods);
    }

    abstract protected function getLevel1FirstItem(ListenerMethod $listener):string;
    abstract protected function getLevel1SecondItem(ListenerMethod $listener):string;

    protected function spaces($spacesCount)
    {
        return str_repeat(' ', $spacesCount);
    }

    protected function prependSlash($className)
    {
        return $className[0] != '\\' ? '\\' . $className : $className;
    }

    /**
     * @param ListenerMethod[] $map
     * @return array
     */
    abstract protected function group(array $map);

}