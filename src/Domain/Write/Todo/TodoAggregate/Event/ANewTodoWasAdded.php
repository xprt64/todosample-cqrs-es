<?php


namespace Domain\Write\Todo\TodoAggregate\Event;


use Dudulina\Event;

class ANewTodoWasAdded implements Event
{
    /**
     * @var string
     */
    private $text;
    /**
     * @var string
     */
    private $id;

    public function __construct(
        string $id,
        string $text
    )
    {
        $this->text = $text;
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getText():string
    {
        return $this->text;
    }
}