<?php

namespace Verbum\Core;

class DispatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var App
     */
    protected $app;

    protected function setUp()
    {
        /** @var App app */
        $this->app = $this->getMockBuilder('\Verbum\Core\App')
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->config = [
            'routes' => [
                'normal' => [
                    'handler' => ['HandlerClass', 'handlerAction']
                ]
            ]
        ];
    }

    public function testNormalDispatchCase()
    {
        $routerMock = $this->prepareRouterMock(['normal', [1, 2, 3]]);

        $fcMock = $this->getMockBuilder('\Verbum\Core\FrontController')
            ->setConstructorArgs([$this->app])
            ->setMethods(['serve'])
            ->getMock();

        $fcMock->expects($this->once())
            ->method('serve')
            ->with($this->equalTo(['HandlerClass', 'handlerAction']));

        $dispatcher = new Dispatcher($this->app);
        $dispatcher->setRouter($routerMock);
        $dispatcher->setFrontController($fcMock);
        $dispatcher->dispatch();

        $this->assertEquals([1, 2, 3], $this->app->getRequest()->getParams());
    }

    public function testNoRouteCase()
    {
        $routerMock = $this->prepareRouterMock(false);

        $fcMock = $this->getMockBuilder('\Verbum\Core\FrontController')
            ->disableOriginalConstructor()
            ->setMethods(['notFound'])
            ->getMock();

        $fcMock->expects($this->once())
            ->method('notFound')
            ->with();

        $dispatcher = new Dispatcher($this->app);
        $dispatcher->setRouter($routerMock);
        $dispatcher->setFrontController($fcMock);
        $dispatcher->dispatch();
    }

    public function testThrowsException()
    {
        $routerMock = $this->prepareRouterMock(false);
        $e = new Exception('Test exception');

        $fcMock = $this->getMockBuilder('\Verbum\Core\FrontController')
            ->disableOriginalConstructor()
            ->setMethods(['notFound', 'exception'])
            ->getMock();

        $fcMock->expects($this->once())
            ->method('notFound')
            ->willThrowException($e);

        $fcMock->expects($this->once())
            ->method('exception')
            ->with($this->equalTo($e));

        $dispatcher = new Dispatcher($this->app);
        $dispatcher->setRouter($routerMock);
        $dispatcher->setFrontController($fcMock);
        $dispatcher->dispatch();
    }

    /**
     * @param $returnValue
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function prepareRouterMock($returnValue)
    {
        $routerMock = $this->getMockBuilder('\Verbum\Core\Router')
            ->disableOriginalConstructor()
            ->setMethods(['findRoute'])
            ->getMock();

        $routerMock->expects($this->any())
            ->method('findRoute')
            ->willReturn($returnValue);
        return $routerMock;
    }
}
