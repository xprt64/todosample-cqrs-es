<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Domain\Write\Todo\DuplicateTodoDetection;


interface ListOfAllTodoNames
{
    /**
     * @param string $todoTitle
     * @return void
     * @throws TodoAlreadyExistsException
     */
    public function tryToAddUniqueTodo(string $todoTitle):void;
}