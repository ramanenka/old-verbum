<?php

namespace Verbum\Core;

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

        $fc = new FrontController($this->app);
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
        $controller = $this->getMockBuilder('Verbum\Core\FrontController')
            ->setMethods(['callHandler'])
            ->disableOriginalConstructor()
            ->getMock();

        $controller->expects($this->once())
            ->method('callHandler')
            ->with($this->isType('array'));

        $controller->notFound();
    }

    public function testException()
    {
        $controller = $this->getMockBuilder('Verbum\Core\FrontController')
            ->setMethods(['callHandler'])
            ->setConstructorArgs([$this->app])
            ->getMock();

        $controller->expects($this->once())
            ->method('callHandler')
            ->with($this->isType('array'));

        $e = new Exception();
        $controller->exception($e);

        $this->assertSame($e, $this->app->getRequest()->getParam('exception'));
    }
}
