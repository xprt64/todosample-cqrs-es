<?php


namespace Domain\Write\Todo\TodoAggregate\Event;


use Dudulina\Event;

class ANewTodoWasAdded implements Event
{
    /**
     * @var
     */
    private $text;

    public function __construct(
        $text
    )
    {
        $this->text = $text;
    }

    public function getText()
    {
        return $this->text;
    }
}