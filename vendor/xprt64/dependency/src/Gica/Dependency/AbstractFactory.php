<?php


namespace Gica\Dependency;


interface AbstractFactory
{
    public function createObject($objectClass, $callerInjectableConstructorArguments = []);
}