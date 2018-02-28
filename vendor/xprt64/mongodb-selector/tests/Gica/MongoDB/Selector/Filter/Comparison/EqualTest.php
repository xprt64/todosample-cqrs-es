<?php

namespace tests\Gica\MongoDB\Selector\Filter\Comparison;

use Gica\MongoDB\Selector\Filter\Comparison\Equal;
use PHPUnit\Framework\TestCase;

class EqualTest extends TestCase
{

    public function test()
    {
        $sut = new Equal(
            'a', 'b'
        );

        $this->assertEquals(
            [
                'a' => [
                    '$eq' => 'b',
                ],
            ],
            $sut->applyFilter([])
        );
    }
}
