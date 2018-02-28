<?php


namespace tests\Gica\CodeAnalysis\MethodListenerDiscovery\ClassSorter\ByClassConstantDependencySorterTest;


use Gica\CodeAnalysis\Shared\ClassSorter\ByClassConstantDependencySorter;


class ByClassConstantDependencySorterTest extends \PHPUnit_Framework_TestCase
{

    public function test()
    {
        $sut = new ByClassConstantDependencySorter('MY_CONSTANT');

        $classes = [
            new \ReflectionClass(Class1::class),
            new \ReflectionClass(Class3::class),
            new \ReflectionClass(Class2::class),
        ];

        /** @var \ReflectionClass[] $classes */
        $classes = $sut->sortClasses($classes);

        $this->assertEquals(Class1::class, $classes[0]->getName());
        $this->assertEquals(Class2::class, $classes[1]->getName());
        $this->assertEquals(Class3::class, $classes[2]->getName());
    }
}

class Class1
{
    const MY_CONSTANT = 1;
}

class Class2
{
    const MY_CONSTANT = 2;
}

class Class3
{
    const MY_CONSTANT = 3;
}