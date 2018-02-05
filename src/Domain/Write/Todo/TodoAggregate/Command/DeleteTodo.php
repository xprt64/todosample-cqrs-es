<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Domain\Write\Todo\TodoAggregate\Command;


use Dudulina\Command;

class DeleteTodo implements Command
{
    /**
     * @var
     */
    private $id;

    public function __construct(
        string $id
    )
    {
        if (empty($id)) {
            throw new \Exception("$id ID must not be empty");
        }
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAggregateId()
    {
        return $this->getId();
    }
}