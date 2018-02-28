<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Domain\Write\Todo\TodoAggregate\Command;


use Dudulina\Command;

class RenameTodo implements Command
{
    /**
     * @var
     */
    private $id;
    /**
     * @var string
     */
    private $text;

    public function __construct(
        string $id,
        string $text
    )
    {
        if (empty($id)) {
            throw new \InvalidArgumentException("$id ID must not be empty");
        }

        $this->id = $id;
        $this->text = $text;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getText():string
    {
        return $this->text;
    }

    public function getAggregateId()
    {
        return $this->getId();
    }
}