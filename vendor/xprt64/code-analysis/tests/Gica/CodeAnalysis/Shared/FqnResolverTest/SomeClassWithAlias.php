<?php


namespace tests\Gica\CodeAnalysis\Shared\FqnResolverTest;


use tests\Gica\CodeAnalysis\Shared\FqnResolverTest\Subdir\SomeOtherClass as MyAlias;

class SomeClassWithAlias
{
    /** @var  MyAlias */
    private $someOtherclass;
}