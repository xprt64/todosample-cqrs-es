<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Domain\Write\Todo\TodoAggregate\Event;


use Gica\Cqrs\Event;

class ATodoWasMarkedAsDone implements Event
{
    public function __construct(
    )
    {
    }
}