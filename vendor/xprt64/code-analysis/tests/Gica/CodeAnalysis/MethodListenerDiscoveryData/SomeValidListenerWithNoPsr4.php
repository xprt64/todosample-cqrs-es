<?php


namespace tests\Gica\CodeAnalysis\MethodListenerDiscoveryData\SomeValidListenerWithNoPsr4;


use tests\Gica\CodeAnalysis\MethodListenerDiscoveryData\Message;

class SomeValidListenerWithNoPsr4
{
    public function xxxSomeMethodWithNoPsr4(MyMessage $message)
    {

    }

    public function notAcceptedMethod()
    {

    }

    public function notAcceptedMethodWithOneArgument(string $someString)
    {

    }

    public function xxxNotAcceptedMethodWithOneArgumentWithTypeHint(\Exception $someArgument)
    {

    }
}

class MyMessage implements Message
{

}