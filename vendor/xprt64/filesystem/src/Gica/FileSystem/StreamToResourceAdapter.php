<?php
/**
 * @copyright  Copyright (c) Galbenu xprt64@gmail.com
 * All rights reserved.
 */

namespace Gica\FileSystem;


class StreamToResourceAdapter
{
    const PROTOCOL = 'StreamToResourceAdapter';
    const DEFAULT_CHUNK_SIZE = 21478364;
    /**
     * @var resource
     * @see http://php.net/manual/ro/class.streamwrapper.php
     */
    public $context;

    /**
     * @var \Psr\Http\Message\StreamInterface
     */
    protected $stream;

    /**
     * @var resource
     */
    protected $resource;

    /**
     * @var self
     */
    protected $instance;

    /**
     * @param \Psr\Http\Message\StreamInterface $stream
     * @return resource
     */
    public function getResourceFromStream(\Psr\Http\Message\StreamInterface $stream)
    {
        $this->setStream($stream);
        $this->registerWrapperIfNecessary();
        return $this->createAndGetResource();
    }

    protected function registerWrapperIfNecessary()
    {
        if (!$this->isWrapperRegistered())
            $this->registerWrapper();
    }

    /**
     * @return bool
     */
    private function isWrapperRegistered()
    {
        return in_array(self::PROTOCOL, stream_get_wrappers());
    }

    private function registerWrapper()
    {
        stream_wrapper_register(self::PROTOCOL, __CLASS__);
    }

    /**
     * @return resource
     */
    private function createAndGetResource()
    {
        $context = $this->createStreamContext();
        return $this->createAndGetResourceWithContext($context);
    }

    /**
     * @return resource
     */
    private function createStreamContext()
    {
        return stream_context_create($this->createContextOptions());
    }

    /**
     * @return array
     */
    private function createContextOptions()
    {
        $options = [];
        $options[self::PROTOCOL]['instance'] = $this;
        return $options;
    }

    /**
     * @param $context resource
     * @return resource
     */
    private function createAndGetResourceWithContext($context)
    {
        $h = fopen(self::PROTOCOL . '://', 'r+', false, $context);
        stream_set_chunk_size($h, self::DEFAULT_CHUNK_SIZE);
        return $h;
    }

    public function stream_open($path, $mode, $options, &$opened_path)
    {
        return true;
    }

    public function stream_read($count)
    {
        return $this->getInstanceStream()->read($count);
    }

    /**
     * @return \Psr\Http\Message\StreamInterface
     * @throws \Exception
     */
    protected function getInstanceStream()
    {
        return $this->getInstance()->getStream();
    }

    /**
     * @return \Psr\Http\Message\StreamInterface
     */
    public function getStream()
    {
        return $this->stream;
    }

    protected function setStream(\Psr\Http\Message\StreamInterface $stream)
    {
        $this->stream = $stream;
    }

    /**
     * @return static
     * @throws \Exception
     */
    protected function getInstance()
    {
        if (!$this->instance)
            $this->instance = $this->extractInstanceFromContext();
        return $this->instance;
    }

    /**
     * @return static
     * @throws \Exception
     */
    private function extractInstanceFromContext()
    {
        if (!$this->context)
            throw new \Exception('Please specify a context on fopen');

        $options = stream_context_get_options($this->context);
        if (!$this->extractInstanceFromContextOptions($options))
            throw new \Exception('Please specify a context with a instance option on fopen');

        return $this->extractInstanceFromContextOptions($options);
    }

    /**
     * @param $options
     * @return mixed
     */
    private function extractInstanceFromContextOptions($options)
    {
        return $options[self::PROTOCOL]['instance'];
    }

    public function stream_set_option(int $option, int $arg1, int $arg2)
    {
        return true;
    }

    public function stream_eof()
    {
        return $this->getInstanceStream()->eof();
    }

    public function stream_close()
    {
        $this->getInstanceStream()->close();
    }

    public function stream_seek(int $offset, int $whence = SEEK_SET)
    {
        try {
            $this->getInstanceStream()->seek($offset, $whence);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function stream_tell()
    {
        return $this->getInstanceStream()->tell();
    }

    public function stream_write(string $data)
    {
        return $this->getInstanceStream()->write($data);
    }

    public function stream_flush()
    {
        return true;
    }
}