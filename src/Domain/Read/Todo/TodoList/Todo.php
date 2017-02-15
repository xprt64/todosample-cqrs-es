<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Domain\Read\Todo\TodoList;


class Todo
{

    private $id;
    private $text;
    private $done;

    public function __construct(
        $id,
        $text,
        $done
    )
    {
        $this->id = $id;
        $this->text = $text;
        $this->done = $done;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getText()
    {
        return $this->text;
    }

    public function isDone()
    {
        return (bool)$this->done;
    }
}