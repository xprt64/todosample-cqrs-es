<?php
/**
 * @copyright  Copyright (c) Galbenu xprt64@gmail.com
 * All rights reserved.
 */

namespace tests\unit\Gica\FileSystem;


class StreamToResourceReadAdapterTest extends \PHPUnit_Framework_TestCase
{

    public function test_getResourceWithInMemoryStream()
    {
        $var = 'abcd';
        $stream = new \Gica\FileSystem\MutableStringStream($var);

        $this->assertEquals('abcd', $stream->__toString());

        $stream->rewind();

        $adapter = new \Gica\FileSystem\StreamToResourceAdapter();

        $res = $adapter->getResourceFromStream($stream);

        $readBytes = fread($res, 10);

        $this->assertEquals('abcd', $readBytes);

        fseek($res, 0, SEEK_SET);
        $wroteCount = fwrite($res, 'xyz');

        $this->assertEquals(3, $wroteCount);

        fseek($res, 0, SEEK_SET);
        $this->assertEquals('xyzd', fread($res, 10));
        $this->assertEquals('xyzd', $var);
    }

    public function test_fseekWithInMemoryStream()
    {
        $var = '123456789';
        $stream = new \Gica\FileSystem\MutableStringStream($var);

        $this->assertEquals('123456789', $stream->__toString());

        $adapter = new \Gica\FileSystem\StreamToResourceAdapter();

        $res = $adapter->getResourceFromStream($stream);

        fseek($res, 0, SEEK_SET);
        $this->assertEquals(0, ftell($res) );

        fseek($res, 1, SEEK_SET);
        $this->assertEquals(1, ftell($res) );

        fseek($res, 0, SEEK_END);
        $this->assertEquals(9, ftell($res) );

    }


    public function test_getResourceWithZendDiactorosStream()
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test_getResourceWithZendDiactorosStream');
        file_put_contents($tempFile, 'abcd');
        $this->assertEquals('abcd', file_get_contents($tempFile));

        $stream = new \Zend\Diactoros\Stream($tempFile, 'r+');

        $this->assertEquals('abcd', $stream->__toString());

        $stream->rewind();

        $adapter = new \Gica\FileSystem\StreamToResourceAdapter();

        $res = $adapter->getResourceFromStream($stream);

        $readBytes = fread($res, 10);

        $this->assertEquals('abcd', $readBytes);

        fseek($res, 0, SEEK_SET);
        $wroteCount = fwrite($res, 'xyz');

        $this->assertEquals(3, $wroteCount);

        fseek($res, 0, SEEK_SET);
        $this->assertEquals('xyzd', fread($res, 10));
        $this->assertEquals('xyzd', file_get_contents($tempFile));
    }
}
