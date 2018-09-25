<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace tests\unit\Gica\Serialize\ObjectSerializer\ObjectWithTraitsConverterTest;


use Gica\Serialize\GicaToMongoTypeSerializers\FromEnum;
use Gica\Serialize\GicaToMongoTypeSerializers\FromGuid;
use Gica\Serialize\GicaToMongoTypeSerializers\FromSet;
use Gica\Serialize\ObjectSerializer\CompositeSerializer;
use Gica\Serialize\ObjectSerializer\ObjectSerializer;


class ObjectWithTraitsConverterTest extends \PHPUnit_Framework_TestCase
{
    /** @var ObjectSerializer */
    private $sut;

    protected function setUp()
    {
        $this->sut = new ObjectSerializer(
            new CompositeSerializer([
            ])
        );
    }

    public function test_object()
    {
        $object = new MyObject('a', 'b', 't1_a', 't1_b', 't2_a', 't2_b');

        $converted = $this->sut->convert($object);

        $this->assertArraySubset([
            'a'    => 'a',
            'b'    => 'b',
            't1_a' => 't1_a',
            't1_b' => 't1_b',
            't2_a' => 't2_a',
            't2_b' => 't2_b',
        ], $converted);
    }
}

class MyObject
{
    use MyTrait1, MyTrait2;

    private $a;
    private $b;

    public function __construct($a, $b, $t1_a, $t1_b, $t2_a, $t2_b)
    {
        $this->a = $a;
        $this->b = $b;
        $this->t1_a = $t1_a;
        $this->t1_b = $t1_b;
        $this->t2_a = $t2_a;
        $this->t2_b = $t2_b;
    }
}

trait MyTrait1
{
    private $t1_a;
    private $t1_b;
}

trait MyTrait2
{
    private $t2_a;
    private $t2_b;
}