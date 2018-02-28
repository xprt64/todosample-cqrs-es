<?php

namespace tests\Gica\MongoDB\Selector\Filter\Logical;

use Gica\MongoDB\Selector\Filter\Comparison\EqualDirect;
use Gica\MongoDB\Selector\Filter\Logical\AndGroup;
use PHPUnit\Framework\TestCase;

class AndGroupTest extends TestCase
{

    public function test()
    {
        $sut = new AndGroup(
            new EqualDirect(
                'a', 1
            ),
            new EqualDirect(
                'b', 2
            )
        );

        $this->assertEquals(
            [
                '$and' => [
                    ['a' => 1],
                    ['b' => 2],
                ],
            ],
            $sut->applyFilter([])
        );

        $this->assertEquals(
            [
                'c'    => 3,
                '$and' => [
                    ['a' => 1],
                    ['b' => 2],
                ],
            ],
            $sut->applyFilter(['c' => 3])
        );

        $this->assertEquals(
            [
                '$and' => [
                    ['c' => 3],
                    ['a' => 1],
                    ['b' => 2],
                ],
            ],
            $sut->applyFilter([
                '$and' => [
                    ['c' => 3],
                ],
            ])
        );
    }
}
