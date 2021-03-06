<?php


namespace UI\Action;

use Domain\Write\Todo\TodoAggregate\Command\MarkTodoAsDone;
use Dudulina\Command\CommandDispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class MarkTodoAsDoneAction
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
            $this->commandDispatcher->dispatchCommand(new MarkTodoAsDone(
                $request->getAttribute('id')
            ));

            return new JsonResponse([
                'success' => true,
            ]);
        } catch (\Throwable $exception) {

            return new JsonResponse([
                'success' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }

}