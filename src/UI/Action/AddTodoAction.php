<?php


namespace UI\Action;

use Domain\Read\Todo\TodoList;
use Domain\Write\Todo\TodoAggregate\Command\AddNewTodo;
use Dudulina\Command\CommandDispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class AddTodoAction
{

    /**
     * @var CommandDispatcher
     */
    private $commandDispatcher;
    /**
     * @var TodoList
     */
    private $todoList;

    public function __construct(
        CommandDispatcher $commandDispatcher,
        TodoList $todoList
    )
    {
        $this->commandDispatcher = $commandDispatcher;
        $this->todoList = $todoList;
    }


    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        try {
            $post = $request->getParsedBody();

            $this->commandDispatcher->dispatchCommand(new AddNewTodo(
                $post['id'],
                $post['text']
            ));

            return new JsonResponse([
                'success' => true,
                'id'      => $post['id'],
                'text'    => $this->todoList->getTodoText($post['id']),
            ]);
        } catch (\Throwable $exception) {

            return new JsonResponse([
                'success' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }

}