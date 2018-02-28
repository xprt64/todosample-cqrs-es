<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Infrastructure\Cqrs\EventDispatcher;


use Dudulina\Event\EventDispatcher;
use Dudulina\Event\EventWithMetaData;

class MutedErrorsDecorator implements EventDispatcher
{

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function dispatchEvent(EventWithMetaData $eventWithMetadata)
    {
        set_error_handler(function () {
        });
        $this->dispatcher->dispatchEvent($eventWithMetadata);
        restore_error_handler();
    }
}