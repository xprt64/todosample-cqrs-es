<?php

namespace UI\Action;

use Domain\Read\Todo\TodoList;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class HomePageAction
{
    private $template;
    /**
     * @var TodoList
     */
    private $todoListReadModel;

    public function __construct(
        TemplateRendererInterface $template,
        TodoList $todoListReadModel
    )
    {
        $this->template = $template;
        $this->todoListReadModel = $todoListReadModel;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        return new HtmlResponse($this->template->render('app::home-page', [
            'todos' => $this->todoListReadModel->getAllTodos()
        ]));
    }
}
