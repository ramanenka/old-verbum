<?php

namespace Verbum\Core;

use Verbum\Core\DI\Container;

class FrontControllerTest extends \PHPUnit_Framework_TestCase
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
    }

    /**
     * @dataProvider callRouteDataProvider
     */
    public function testCallRoute(
        $handler,
        $params,
        $expectedResponseBody,
        $expectedResponseHeaders,
        $expectException
    ) {
        if ($expectException) {
            $this->setExpectedException('\Verbum\Core\Exception');
        }

        $this->app->config['action_result_processor'] = '\Verbum\Core\FrontControllerTest\ActionResultProcessor';

        $fc = new FrontController();
        $fc->setApp($this->app);

        $container = new Container();
        $container->set('response', $this->app->getResponse());
        $fc->setContainer($container);
        $this->app->getRequest()->setParams($params);

        $fc->serve($handler);
        $response = $this->app->getResponse();

        if (!$expectException) {
            $this->assertEquals($expectedResponseBody, $response->getContent());
            $this->assertEquals($expectedResponseHeaders, $response->getHeaders());
        }
    }

    public function callRouteDataProvider()
    {
        return [
            [
                ['\Verbum\Core\FrontControllerTestTestController', 'noParamsAction'],
                [],
                json_encode(['result']),
                ['Content-Type' => 'application/json'],
                false
            ],
            [
                ['\Verbum\Core\FrontControllerTestTestController', 'urlParamsAction'],
                ['param1' => 'value1', 'param2' => 'value2'],
                json_encode(['value1', 'value2']),
                ['Content-Type' => 'application/json'],
                false
            ],
            [
                ['\Verbum\Core\FrontControllerTestTestController', 'urlParamsActionWithDefault'],
                ['param1' => 'value1'],
                json_encode(['value1', 'value2']),
                ['Content-Type' => 'application/json'],
                false
            ],
            [
                ['\Verbum\Core\FrontControllerTestTestController', 'urlParamsActionNoRequired'],
                ['param1' => 'value1'],
                json_encode(['value1', 'value2']),
                ['Content-Type' => 'application/json'],
                true
            ],
            [
                ['\Verbum\Core\FrontControllerTestTestController', 'objectParamsAction'],
                ['param1' => 'value1'],
                json_encode([true]),
                ['Content-Type' => 'application/json'],
                false
            ],
            [
                ['\Verbum\Core\FrontControllerTestTestController', 'objectParamsUrlParamsAction'],
                ['param1' => 'value1'],
                json_encode([true, 'value1', 10]),
                ['Content-Type' => 'application/json'],
                false
            ],
            [
                ['\Verbum\Core\FrontControllerTestTestController', 'objectFromParamsWithTypeHintAction'],
                ['exception' => new \Exception()],
                json_encode(['Exception']),
                ['Content-Type' => 'application/json'],
                false
            ],
        ];
    }

    public function testNotFound()
    {
        $controller = $this->getMock('Verbum\Core\FrontController', ['callHandler']);

        $controller->expects($this->once())
            ->method('callHandler')
            ->with($this->isType('array'));

        $controller->notFound();
    }

    public function testException()
    {
        /** @var FrontController|\PHPUnit_Framework_MockObject_MockObject $controller */
        $controller = $this->getMock('Verbum\Core\FrontController', ['callHandler']);
        $controller->setApp($this->app);

        $controller->expects($this->once())
            ->method('callHandler')
            ->with($this->isType('array'));

        $e = new Exception();
        $controller->exception($e);

        $this->assertSame($e, $this->app->getRequest()->getParam('exception'));
    }

    public function testActionResultInvocation()
    {
        $this->app->config['action_result_processor'] = '\Some\Action\Result\Processor\Class';

        $actionResultProcessor = $this->getMock('\Verbum\Core\ActionResultProcessor', ['process']);
        $actionResultProcessor->expects($this->once())
            ->method('process')
            ->with('action-result-1');

        /** @var Container|\PHPUnit_Framework_MockObject_MockObject $container */
        $container = $this->getMock('\Verbum\Core\DI\Container', ['get']);
        $container->expects($this->once())
            ->method('get')
            ->willReturnMap([['\Some\Action\Result\Processor\Class', $actionResultProcessor]]);

        /** @var FrontController|\PHPUnit_Framework_MockObject_MockObject $controller */
        $controller = $this->getMock('Verbum\Core\FrontController', ['callHandler']);
        $controller->expects($this->once())
            ->method('callHandler')
            ->willReturn('action-result-1');
        $controller->setApp($this->app);
        $controller->setContainer($container);

        $controller->serve(['some_handler']);
    }
}
