<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace tests\unit\Gica\Cqrs;


use Gica\Lib\ObjectToArrayConverter;

class ObjectToJsonConverterTest extends \PHPUnit_Framework_TestCase
{

    public function test()
    {
        $serializer = new ObjectToArrayConverter();

        $event = new MyEvent('xxx', 2);

        $expected = [
            'a' => 'xxx',
            'b' => 2,
        ];

        $this->assertEquals($expected, $serializer->convert($event));

    }

    public function test2()
    {
        $serializer = new ObjectToArrayConverter();

        $event = new MyDeepEvent(new MyEvent('xxx', 2), 4);

        $expected = [
            'c' => [
                'a' => 'xxx',
                'b' => 2,
            ],
            'd' => 4,
        ];

        $this->assertEquals($expected, $serializer->convert($event));

    }
}

class MyEvent
{
    private $a;
    private $b;

    /**
     * MyEvent constructor.
     * @param $a
     * @param $b
     */
    public function __construct($a, $b)
    {
        $this->a = $a;
        $this->b = $b;
    }
}

class MyDeepEvent
{
    private $c;
    private $d;

    /**
     * MyEvent constructor.
     * @param $c
     * @param $d
     */
    public function __construct(MyEvent $c, $d)
    {
        $this->c = $c;
        $this->d = $d;
    }
}