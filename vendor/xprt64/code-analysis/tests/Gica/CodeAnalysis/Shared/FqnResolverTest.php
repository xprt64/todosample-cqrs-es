<?php


namespace tests\Gica\CodeAnalysis\Shared;


use Gica\CodeAnalysis\Shared\FqnResolver;
use tests\Gica\CodeAnalysis\Shared\FqnResolverTest\SomeUsedClassInTheSameDir;


require_once __DIR__ . '/FqnResolverTest/SomeClass.php';
require_once __DIR__ . '/FqnResolverTest/SomeClassWithAlias.php';
require_once __DIR__ . '/FqnResolverTest/SomeClassWithPartialAlias.php';
require_once __DIR__ . '/FqnResolverTest/Subdir/SomeOtherClass.php';
require_once __DIR__ . '/FqnResolverTest/Subdir/Subdir2/SomeClassInSubdir2.php';

class FqnResolverTest extends \PHPUnit_Framework_TestCase
{

    public function test()
    {
        $sut = new FqnResolver();

        $this->assertEquals(
            \tests\Gica\CodeAnalysis\Shared\FqnResolverTest\Subdir\SomeOtherClass::class,
            $sut->resolveShortClassName(
                'SomeOtherClass',
                new \ReflectionClass(
                    \tests\Gica\CodeAnalysis\Shared\FqnResolverTest\SomeClassWithUseAndNoAlias::class
                )
            )
        );
    }

    public function testIntheSameDir()
    {
        $sut = new FqnResolver();

        $this->assertEquals(
            SomeUsedClassInTheSameDir::class,
            $sut->resolveShortClassName(
                'SomeUsedClassInTheSameDir',
                new \ReflectionClass(\tests\Gica\CodeAnalysis\Shared\FqnResolverTest\SomeClass::class
                )
            )
        );
    }

    public function test_withAlias()
    {
        $sut = new FqnResolver();

        $this->assertEquals(
            \tests\Gica\CodeAnalysis\Shared\FqnResolverTest\Subdir\SomeOtherClass::class,
            $sut->resolveShortClassName(
                'MyAlias',
                new \ReflectionClass(
                    \tests\Gica\CodeAnalysis\Shared\FqnResolverTest\SomeClassWithAlias::class
                )
            )
        );
    }

    public function test_withPartialAlias()
    {
        $sut = new FqnResolver();

        $this->assertEquals(
            \tests\Gica\CodeAnalysis\Shared\FqnResolverTest\Subdir\Subdir2\SomeClassInSubdir2::class,
            $sut->resolveShortClassName(
                'MyAlias2\Subdir2\SomeClassInSubdir2',
                new \ReflectionClass(
                    \tests\Gica\CodeAnalysis\Shared\FqnResolverTest\SomeClassWithPartialAlias::class
                )
            )
        );
    }
}
