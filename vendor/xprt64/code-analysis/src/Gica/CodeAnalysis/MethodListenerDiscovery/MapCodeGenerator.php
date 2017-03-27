<?php


namespace Gica\CodeAnalysis\MethodListenerDiscovery;


interface MapCodeGenerator
{
    /**
     * @param ListenerMethod[] $map
     * @param string $template
     * @return string
     */
    public function generateAndGetFileContents(array $map, $template);
}