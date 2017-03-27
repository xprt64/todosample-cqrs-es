<?php


namespace tests\Gica\CodeAnalysis\Shared\FqnResolverTest;


use tests\Gica\CodeAnalysis\Shared\FqnResolverTest\Subdir as MyAlias2;

class SomeClassWithPartialAlias
{
    /** @var  MyAlias2\Subdir2\SomeClassInSubdir2 */
    private $someOtherclass;
}