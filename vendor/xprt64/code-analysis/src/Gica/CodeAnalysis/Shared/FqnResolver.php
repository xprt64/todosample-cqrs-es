<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\CodeAnalysis\Shared;


class FqnResolver
{
    public function resolveShortClassName($shortName, \ReflectionClass $contextClass)
    {
        if (preg_match_all('#use\s+(?P<uses>[^;]+);#ims', file_get_contents($contextClass->getFileName()), $m)) {

            $firstComponent = $this->getFirstComponent($shortName);

            foreach ($m['uses'] as $fullUse) {

                list($use, $alias) = $this->parseUse($fullUse);

                if ($alias == $firstComponent) {
                    $withoutFirstComponent = $this->getWithoutFirstComponent($shortName);

                    if ($withoutFirstComponent) {
                        return $use . '\\' . $withoutFirstComponent;
                    } else {
                        return $use;
                    }
                }
            }
        }

        return $contextClass->getNamespaceName() . '\\' . $shortName;
    }

    private function parseUse($fullUse)
    {
        if (false !== stripos($fullUse, ' as ')) {
            list($use, $alias) = explode(' as ', $fullUse);

            $use = trim($use, " ");
            $alias = trim($alias, " ");
        } else {
            $alias = $this->getLastComponent($fullUse);
            $use = $fullUse;
        }

        return [$use, $alias];
    }

    private function getLastComponent($name)
    {
        $parts = explode('\\', $name);

        return $parts[count($parts) - 1];
    }

    private function getWithoutFirstComponent($name)
    {
        $parts = explode('\\', $name);

        return implode('\\', array_slice($parts, 1));
    }

    private function getFirstComponent($name)
    {
        $parts = explode('\\', $name);

        return $parts[0];
    }
}