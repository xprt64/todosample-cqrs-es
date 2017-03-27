<?php


namespace tests\Gica\CodeAnalysis\Shared\ClassComparison;


use Gica\CodeAnalysis\Shared\ClassComparison\SubclassComparator;


class SubclassComparatorTest extends \PHPUnit_Framework_TestCase
{
    public function test_isASubClassOrSameClass()
    {
        $sut = new SubclassComparator();

        $this->assertTrue($sut->isASubClassOrSameClass(new SubClass, ParentClass::class));
        $this->assertTrue($sut->isASubClassOrSameClass(SubClass::class, ParentClass::class));

        $this->assertTrue($sut->isASubClassOrSameClass(new SubClass, ParentInterface::class));
        $this->assertTrue($sut->isASubClassOrSameClass(SubClass::class, ParentInterface::class));

        $this->assertTrue($sut->isASubClassOrSameClass(new ParentClass, ParentClass::class));
        $this->assertTrue($sut->isASubClassOrSameClass(ParentClass::class, ParentClass::class));

        $this->assertTrue($sut->isASubClassOrSameClass(new ParentClass, ParentInterface::class));
        $this->assertTrue($sut->isASubClassOrSameClass(ParentClass::class, ParentInterface::class));

        //////////////////////////////////////////

        $this->assertFalse($sut->isASubClassOrSameClass(new SomeTopLevelClass, ParentClass::class));
        $this->assertFalse($sut->isASubClassOrSameClass(SomeTopLevelClass::class, ParentClass::class));

        $this->assertFalse($sut->isASubClassOrSameClass(new SomeTopLevelClass, ParentInterface::class));
        $this->assertFalse($sut->isASubClassOrSameClass(SomeTopLevelClass::class, ParentInterface::class));
    }

    public function test_isASubClassButNoSameClass()
    {
        $sut = new SubclassComparator();

        $this->assertTrue($sut->isASubClassButNoSameClass(new SubClass, ParentClass::class));
        $this->assertTrue($sut->isASubClassButNoSameClass(SubClass::class, ParentClass::class));

        $this->assertTrue($sut->isASubClassButNoSameClass(new SubClass, ParentInterface::class));
        $this->assertTrue($sut->isASubClassButNoSameClass(SubClass::class, ParentInterface::class));

        $this->assertTrue($sut->isASubClassButNoSameClass(new ParentClass, ParentInterface::class));
        $this->assertTrue($sut->isASubClassButNoSameClass(ParentClass::class, ParentInterface::class));

        //////////////////////////////////////////

        $this->assertFalse($sut->isASubClassButNoSameClass(new ParentClass, ParentClass::class));
        $this->assertFalse($sut->isASubClassButNoSameClass(ParentClass::class, ParentClass::class));

        $this->assertFalse($sut->isASubClassButNoSameClass(new SomeTopLevelClass, ParentClass::class));
        $this->assertFalse($sut->isASubClassButNoSameClass(SomeTopLevelClass::class, ParentClass::class));

        $this->assertFalse($sut->isASubClassButNoSameClass(new SomeTopLevelClass, ParentInterface::class));
        $this->assertFalse($sut->isASubClassButNoSameClass(SomeTopLevelClass::class, ParentInterface::class));
    }
}

interface ParentInterface
{

}

class ParentClass implements ParentInterface
{

}

class SubClass extends ParentClass
{

}

class SomeTopLevelClass
{

}