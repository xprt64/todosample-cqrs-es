<?php
////////////////////////////////////////////////////////////////////////////////
// Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>              /
////////////////////////////////////////////////////////////////////////////////

namespace tests\Gica\Dependency;


use Gica\Dependency\ConstructorAbstractFactory;

class ConstructorAbstractFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testNoCallerArguments()
    {
        $container = new Container();

        $factory = new ConstructorAbstractFactory($container);

        $instance = $factory->createObject(TestObject1::class);

        $this->assertInstanceOf(Class1::class, $instance->p1);
        $this->assertInstanceOf(Class2::class, $instance->p2);
        $this->assertInstanceOf(Class3::class, $instance->p3);
        $this->assertSame(5, $instance->p4);
    }

    public function testWithCallerArguments()
    {
        $container = new Container();

        $factory = new ConstructorAbstractFactory($container);

        $instance = $factory->createObject(TestObject1::class, [new SubClass1()]);

        $this->assertInstanceOf(SubClass1::class, $instance->p1);
        $this->assertInstanceOf(Class2::class, $instance->p2);
        $this->assertInstanceOf(Class3::class, $instance->p3);
        $this->assertSame(5, $instance->p4);
    }

    /**
     * @expectedExceptionMessage stdClass not found in container
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionIsThrown()
    {
        $container = new Container();

        $factory = new ConstructorAbstractFactory($container);

        $instance = $factory->createObject(TestObject2::class);
    }

    /**
     * @expectedExceptionMessageRegExp  #is not type-hinted and does not have default value#ims
     * @expectedException \Exception
     */
    public function testExceptionIsThrownForScalar()
    {
        $container = new Container();

        $factory = new ConstructorAbstractFactory($container);

        $instance = $factory->createObject(TestObject3::class);
    }

    public function testWithoutConstructor()
    {
        $container = new Container();

        $factory = new ConstructorAbstractFactory($container);

        $instance = $factory->createObject(TestObjectWithoutConstructor::class);

        $this->assertInstanceOf(TestObjectWithoutConstructor::class, $instance);
    }
}

class TestObject1
{
    public $p1;
    public $p2;
    public $p3;
    public $p4;

    public function __construct(Interface1 $p1, Class2 $p2, Class3 $p3, $p4 = 5)
    {
        $this->p1 = $p1;
        $this->p2 = $p2;
        $this->p3 = $p3;
        $this->p4 = $p4;
    }
}

class TestObject2
{
    public function __construct(Interface1 $p1, Class2 $p2, \stdClass $p3)
    {
    }
}

class TestObject3
{
    public function __construct(Interface1 $p1, Class2 $p2, $p3)
    {
    }
}

class TestObjectWithoutConstructor
{
}

interface Interface1
{

}

class Class1 implements Interface1
{

}

class SubClass1 extends Class1
{

}

class Class2
{

}

class Class3
{

}

class Container implements \Interop\Container\ContainerInterface
{

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        if (Class1::class === $id || Interface1::class === $id)
            return new Class1();
        if (Class2::class === $id)
            return new Class2();
        if (Class3::class === $id)
            return new Class3();

        throw new \InvalidArgumentException("$id not found in container");
    }

    /**
     * @inheritdoc
     */
    public function has($id)
    {
        try {
            $this->get($id);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
