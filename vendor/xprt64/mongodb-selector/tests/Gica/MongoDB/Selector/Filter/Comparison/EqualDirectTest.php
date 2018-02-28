<?php

namespace tests\Gica\MongoDB\Selector\Filter\Comparison;

use Gica\MongoDB\Selector\Filter\Comparison\EqualDirect;
use PHPUnit\Framework\TestCase;

class EqualDirectTest extends TestCase
{

    public function test()
    {
        $sut = new EqualDirect(
            'a', 'b'
        );

        $this->assertEquals(
            [
                'a' => 'b',
            ],
            $sut->applyFilter([])
        );
    }
}
