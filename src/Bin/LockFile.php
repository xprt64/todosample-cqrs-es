<?php

/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Bin;

class LockFile
{
    protected $name;

    protected $f;

    private $path;

    protected $locked = false;

    function __construct($name)
    {
        $this->name = md5($name);
    }

    function __destruct()
    {
        $this->close();
    }

    function close()
    {
        if ($this->f) {
            if ($this->locked)
                flock($this->f, LOCK_UN);

            fclose($this->f);
            $this->f = null;
            $this->locked = false;
        }
    }

    public function open()
    {
        if ($this->f)
            return true;

        $this->close();

        $this->locked = false;

        $dir = '/tmp/lock';

        if (!is_dir($dir))
            mkdir($dir, 0777);

        $this->path = $dir . '/crm_' . $this->name;

        $this->f = fopen($this->path, "c");

        if (false === $this->f) {
            throw new \Exception("fopen $this->name($this->path) failed");
        }

        stream_set_blocking($this->f, 0);

        if (file_exists($this->path))
            @chmod($this->path, 0777);

        return true;
    }

    public function tryLock()
    {
        if (!$this->open())
            return false;

        if (!$this->f) {
            throw new \Exception("File not open; open the file first!");
        }

        if (flock($this->f, LOCK_EX | LOCK_NB))
            $this->locked = true;
        else
            $this->locked = false;

        return $this->locked;
    }
}
