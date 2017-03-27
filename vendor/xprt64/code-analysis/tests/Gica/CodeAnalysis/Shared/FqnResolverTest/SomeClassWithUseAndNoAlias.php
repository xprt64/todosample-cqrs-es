<?php


namespace tests\Gica\CodeAnalysis\Shared\FqnResolverTest;


use tests\Gica\CodeAnalysis\Shared\FqnResolverTest\Subdir\SomeOtherClass;

class SomeClassWithUseAndNoAlias
{
    /** @var  SomeOtherClass */
    private $someOtherclass;
}