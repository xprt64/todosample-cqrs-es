<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

declare(strict_types=1);

namespace Domain\Query\Todo;

class WhatIsTheStatusOfTheTodo
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var bool
     */
    private $done;

    public function __construct(
        string $id
    )
    {
        $this->id = $id;
    }

    public function withAnswer(bool $done):self
    {
        $other = clone $this;
        $other->done = $done;
        return $other;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return bool The answer
     */
    public function isDone(): bool
    {
        return $this->done;
    }
}