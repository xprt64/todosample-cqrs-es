<?php


namespace tests\Gica\CodeAnalysis\MethodListenerDiscoveryData;


class SomeValidListener
{
    public function xxxSomeMethod(MyMessage $message)
    {

    }
}

class MyMessage implements Message
{

}