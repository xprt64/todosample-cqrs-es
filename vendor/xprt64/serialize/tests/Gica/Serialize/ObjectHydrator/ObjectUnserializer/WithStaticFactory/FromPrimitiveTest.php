<?php

namespace tests\Gica\Serialize\ObjectHydrator\ObjectUnserializer\WithStaticFactory;

use Gica\Serialize\ObjectHydrator\Exception\ValueNotUnserializable;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer\WithStaticFactory\FromPrimitive;

class FromPrimitiveTest extends \PHPUnit_Framework_TestCase
{

    public function test()
    {
        $sut = new FromPrimitive();

        $result = $sut->tryToUnserializeValue(WithFromPrimitive::class, '1');

        $this->assertInstanceOf(WithFromPrimitive::class, $result);
        $this->assertSame(1, $result->getValue());
    }

    public function test_ValueNotUnserializable()
    {
        $sut = new FromPrimitive();

        $this->expectException(ValueNotUnserializable::class);
        $sut->tryToUnserializeValue(\stdClass::class, '1');
    }
}

class WithFromPrimitive
{

    /**
     * @var int
     */
    private $value;

    private function __construct(
        int $value
    )
    {
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public static function fromPrimitive($a)
    {
        return new static(intval($a));
    }
}
