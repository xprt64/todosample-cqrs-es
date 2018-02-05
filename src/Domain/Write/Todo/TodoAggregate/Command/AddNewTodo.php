<?php


namespace Domain\Write\Todo\TodoAggregate\Command;


use Dudulina\Command;

class AddNewTodo implements Command
{
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $text;

    public function __construct(
        string $id,
        $text
    )
    {
        if (empty($id)) {
            throw new \Exception("$id ID must not be empty");
        }

        $this->id = $id;
        $this->text = $text;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getAggregateId()
    {
        return $this->getId();
    }
}