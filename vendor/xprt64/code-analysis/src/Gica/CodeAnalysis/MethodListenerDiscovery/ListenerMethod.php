<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\CodeAnalysis\MethodListenerDiscovery;


class ListenerMethod
{

    private $className;
    private $methodName;
    private $eventClassName;
    /**
     * @var \ReflectionClass
     */
    private $class;

    public function __construct(
        \ReflectionClass
        $class, $methodName, $eventClassName
    )
    {
        $this->className = $class->name;
        $this->methodName = $methodName;
        $this->eventClassName = $eventClassName;
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->class->name;
    }

    /**
     * @return \ReflectionClass
     */
    public function getClass(): \ReflectionClass
    {
        return $this->class;
    }

    /**
     * @return mixed
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * @return mixed
     */
    public function getEventClassName()
    {
        return $this->eventClassName;
    }


}