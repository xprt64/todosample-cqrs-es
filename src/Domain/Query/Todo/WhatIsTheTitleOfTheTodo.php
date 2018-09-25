<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

declare(strict_types=1);

namespace Domain\Query\Todo;

class WhatIsTheTitleOfTheTodo
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string|null
     */
    private $answer;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function withAnswer(?string $title): self
    {
        $other = clone $this;
        $other->answer = $title;
        return $other;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }
}