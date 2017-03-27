<?php
////////////////////////////////////////////////////////////////////////////////
// Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>              /
////////////////////////////////////////////////////////////////////////////////

namespace Gica\Xss;


class ObjectDecorator
{
    private $decoratedObject;

    public function __construct($decoratedObject)
    {
        $this->decoratedObject = $decoratedObject;
    }

    public function __call($name, array $arguments)
    {
        $originalMethod = [$this->decoratedObject, $name];

        $result = call_user_func_array($originalMethod, $arguments);

        $result = $this->escape($result);

        return $result;
    }

    private function escape($result)
    {
        if (is_string($result))
            $result = htmlentities($result, ENT_QUOTES, 'utf-8');
        else if (is_array($result)) {
            $result = array_map(function ($item) {
                if (is_object($item)) {
                    return new self($item);
                }
                return $this->escape($item);
            }, $result);
        }
        return $result;
    }
}