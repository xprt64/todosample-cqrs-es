<?php

namespace AppTest\Action;

use UI\Action\HomePageAction;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Expressive\Router\RouterInterface;

class HomePageActionTest extends \PHPUnit_Framework_TestCase
{
    /** @var RouterInterface */
    protected $router;

    protected function setUp()
    {
        $this->router = $this->prophesize(RouterInterface::class);
    }

    public function testResponse()
    {
        $renderer = $this->getMockBuilder(\Zend\Expressive\Template\TemplateRendererInterface::class)->getMock();
        $renderer
            ->method('render')
            ->willReturn('test');

        $todoListReadModel = $this->getMockBuilder(\Domain\Read\Todo\TodoList::class)
            ->disableOriginalConstructor()
            ->getMock();

        $homePage = new HomePageAction($renderer, $todoListReadModel);
        $response = $homePage(new ServerRequest(['/']), new Response(), function () {
        });

        $this->assertTrue($response instanceof Response);
    }
}
