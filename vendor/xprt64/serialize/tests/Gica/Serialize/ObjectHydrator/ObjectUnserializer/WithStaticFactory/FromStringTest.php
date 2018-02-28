<?php

namespace tests\Gica\Serialize\ObjectHydrator\ObjectUnserializer\WithStaticFactory;

use Gica\Serialize\ObjectHydrator\Exception\ValueNotUnserializable;
use Gica\Serialize\ObjectHydrator\ObjectUnserializer\WithStaticFactory\FromString;

class FromStringTest extends \PHPUnit_Framework_TestCase
{

    public function test()
    {
        $sut = new FromString();

        $result = $sut->tryToUnserializeValue(WithFromString::class, '1');

        $this->assertInstanceOf(WithFromString::class, $result);
        $this->assertSame(1, $result->getValue());
    }

    public function test_ValueNotUnserializable()
    {
        $sut = new FromString();

        $this->expectException(ValueNotUnserializable::class);
        $sut->tryToUnserializeValue(\stdClass::class, '1');
    }
}

class WithFromString
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

    public static function fromString($a)
    {
        return new static(intval($a));
    }
}
