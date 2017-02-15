<?php


namespace UI\Action;

use Domain\Write\Todo\TodoAggregate\Command\AddNewTodo;
use Gica\Cqrs\Command\CommandDispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class AddTodoAction
{

    /**
     * @var CommandDispatcher
     */
    private $commandDispatcher;

    public function __construct(
        CommandDispatcher $commandDispatcher
    )
    {
        $this->commandDispatcher = $commandDispatcher;
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
                'text'    => $post['text'],
            ]);
        } catch (\Throwable $exception) {

            return new JsonResponse([
                'success' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }

}