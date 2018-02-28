<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Domain\Read\Todo\TodoList;


class TodoFactory
{
    public function __invoke($document): Todo
    {
        return new Todo(
            $document['_id'],
            $document['text'],
            $document['done']
        );
    }
}