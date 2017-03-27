<?php


namespace tests\Gica\CodeAnalysis\MethodListenerDiscovery\ClassSorter\ByConstructorDependencySorterTest;


use Gica\CodeAnalysis\Shared\ClassSorter\ByConstructorDependencySorter;


class ByConstructorDependencySorterTest extends \PHPUnit_Framework_TestCase
{

    public function test()
    {
        $sut = new ByConstructorDependencySorter();

        $classes = [
            new \ReflectionClass(Class1::class),
            new \ReflectionClass(Class3::class),
            new \ReflectionClass(Class2::class),
        ];

        usort($classes, $sut);

        $this->assertEquals(Class1::class, $classes[0]->getName());
        $this->assertEquals(Class2::class, $classes[1]->getName());
        $this->assertEquals(Class3::class, $classes[2]->getName());
    }
}

class Class1
{

}

class Class2
{

    public function __construct(
        Class1 $class1
    )
    {
    }
}

class Class3
{

    public function __construct(
        Class2 $class1
    )
    {
    }
}