<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace tests\unit\Gica\Serialize\ObjectSerializer\ObjectWithPrivateParentsConverterTest;


use Gica\Serialize\GicaToMongoTypeSerializers\FromEnum;
use Gica\Serialize\GicaToMongoTypeSerializers\FromGuid;
use Gica\Serialize\GicaToMongoTypeSerializers\FromSet;
use Gica\Serialize\ObjectSerializer\CompositeSerializer;
use Gica\Serialize\ObjectSerializer\ObjectSerializer;



class ObjectWithPrivateParentsConverterTest extends \PHPUnit_Framework_TestCase
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

class MyParent2
{
    private $t2_a;
    private $t2_b;

    public function __construct($t2_a, $t2_b)
    {
        $this->t2_a = $t2_a;
        $this->t2_b = $t2_b;
    }
}

class MyParent1 extends MyParent2
{
    private $t1_a;
    private $t1_b;

    public function __construct($t1_a, $t1_b, $t2_a, $t2_b)
    {
        parent::__construct($t2_a, $t2_b);
        $this->t1_a = $t1_a;
        $this->t1_b = $t1_b;
    }
}

class MyObject extends MyParent1
{
    private $a;
    private $b;

    public function __construct($a, $b, $t1_a, $t1_b, $t2_a, $t2_b)
    {
        $this->a = $a;
        $this->b = $b;
        parent::__construct($t1_a, $t1_b, $t2_a, $t2_b);
    }
}