<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Domain\Write\Todo\TodoAggregate\Event;

use Dudulina\Event;

class ATodoWasMarkedAsDone implements Event
{
    /**
     * @var string
     */
    private $id;

    public function __construct(
        string $id
    )
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}