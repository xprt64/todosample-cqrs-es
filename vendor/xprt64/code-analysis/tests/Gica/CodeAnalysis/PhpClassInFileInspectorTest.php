<?php


namespace tests\Gica\CodeAnalysis;


use Gica\CodeAnalysis\PhpClassInFileInspector;
use tests\Gica\CodeAnalysis\PhpClassInFileInspectorData\GoodClass;
use tests\Gica\radomNamespace\GoodClassButNoPsr4;


class PhpClassInFileInspectorTest extends \PHPUnit_Framework_TestCase
{

    public function test()
    {
        $sut = new PhpClassInFileInspector();

        $this->assertEquals('\\' . GoodClass::class, $sut->getFullyQualifiedClassName(__DIR__ . '/PhpClassInFileInspectorData/GoodClass.php'));
    }

    public function testGoodClassButNoPsr4()
    {
        $sut = new PhpClassInFileInspector();

        $this->assertEquals('\\' . GoodClassButNoPsr4::class, $sut->getFullyQualifiedClassName(__DIR__ . '/PhpClassInFileInspectorData/GoodClassButNoPsr4.php'));
    }

    public function testNoNamespace()
    {
        $sut = new PhpClassInFileInspector();

        $this->assertSame(null, $sut->getFullyQualifiedClassName(__DIR__ . '/PhpClassInFileInspectorData/NoNamespace.php'));
    }

    public function testNoClass()
    {
        $sut = new PhpClassInFileInspector();

        $this->assertSame(null, $sut->getFullyQualifiedClassName(__DIR__ . '/PhpClassInFileInspectorData/NoClass.php'));
    }

    public function testClassInCode()
    {
        $sut = new PhpClassInFileInspector();

        $this->assertSame(null, $sut->getFullyQualifiedClassName(__DIR__ . '/PhpClassInFileInspectorData/ClassInCode.php'));
    }
}
