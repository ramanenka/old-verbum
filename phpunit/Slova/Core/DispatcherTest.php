<?php

namespace Slova\Core;

class DispatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var App
     */
    protected $app;

    protected function setUp()
    {
        /** @var App app */
        $this->app = $this->getMockBuilder('\Slova\Core\App')
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
        $routerMock = $this->prepareRouterMock(['name' => 'normal', 'params' => [1, 2, 3]]);

        $fcMock = $this->getMockBuilder('\Slova\Core\FrontController')
            ->setConstructorArgs([$this->app])
            ->setMethods(['serve'])
            ->getMock();

        $fcMock->expects($this->once())
            ->method('serve')
            ->with($this->equalTo(['HandlerClass', 'handlerAction']), $this->equalTo([1, 2, 3]));

        $dispatcher = new Dispatcher($this->app);
        $dispatcher->setRouter($routerMock);
        $dispatcher->setFrontController($fcMock);
        $dispatcher->dispatch();
    }

    public function testNoRouteCase()
    {
        $routerMock = $this->prepareRouterMock(false);

        $fcMock = $this->getMockBuilder('\Slova\Core\FrontController')
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

        $fcMock = $this->getMockBuilder('\Slova\Core\FrontController')
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

    public function testDispatchLoopEnds()
    {
        $routerMock = $this->prepareRouterMock(['name' => 'normal', 'params' => [1, 2, 3]]);

        $fcMock = $this->getMockBuilder('\Slova\Core\FrontController')
            ->setConstructorArgs([$this->app])
            ->setMethods(['serve', 'exception'])
            ->getMock();

        $e = new ForwardException('normal', []);

        $fcMock->expects($this->any())
            ->method('serve')
            ->willThrowException($e);

        $fcMock->expects($this->any())
            ->method('exception')
            ->with($this->callback(function($e) {
                return is_a($e, '\Slova\Core\Exception');
            }));

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
        $routerMock = $this->getMockBuilder('\Slova\Core\Router')
            ->disableOriginalConstructor()
            ->setMethods(['findRoute'])
            ->getMock();

        $routerMock->expects($this->any())
            ->method('findRoute')
            ->will($this->returnValue($returnValue));
        return $routerMock;
    }
}
