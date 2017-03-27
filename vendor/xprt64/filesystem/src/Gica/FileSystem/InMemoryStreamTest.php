<?php
/**
 * @copyright  Copyright (c) Galbenu xprt64@gmail.com
 * All rights reserved.
 */

namespace tests\unit\Gica\FileSystem;


class InMemoryStreamTest extends \PHPUnit_Framework_TestCase
{

    public function test_seek()
    {
        $var = '123456789';

        $stream = new \Gica\FileSystem\MutableStringStream($var);

        $this->assertEquals(0, $stream->tell());

        $stream->seek(1, SEEK_SET);

        $this->assertEquals(1, $stream->tell());

        $stream->seek(1, SEEK_CUR);

        $this->assertEquals(2, $stream->tell());

        $stream->seek(2, SEEK_CUR);

        $this->assertEquals(4, $stream->tell());

        $stream->seek(0, SEEK_END);

        $this->assertEquals(9, $stream->tell());

    }

    public function test_write()
    {
        $var = '';

        $stream = new \Gica\FileSystem\MutableStringStream($var);

        $this->assertEquals(0, $stream->tell());

        $stream->write('123');

        $this->assertEquals('123', $stream->__toString());

        $stream->write('abc');

        $this->assertEquals('123abc', $stream->__toString());

        $stream->seek(0, SEEK_SET);

        $stream->write('987');

        $this->assertEquals('987abc', $stream->__toString());

        $stream->write('defg');

        $this->assertEquals('987defg', $stream->__toString());

        $this->assertEquals('987defg', $var);

        $this->assertTrue($stream->eof());
    }

    public function test_read()
    {
        $var = '123456789';

        $stream = new \Gica\FileSystem\MutableStringStream($var);

        $this->assertEquals(0, $stream->tell());

        $this->assertEquals('123', $stream->read(3));

        $this->assertEquals('456', $stream->read(3));

        $this->assertEquals('789', $stream->read(10));

        $this->assertEquals('', $stream->read(100));

    }
}
