<?php

namespace Slova\Core;


class FrontControllerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var App
     */
    protected $app;

    protected function setUp() {
        /** @var App app */
        $this->app = $this->getMockBuilder('\Slova\Core\App')
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @dataProvider callRouteDataProvider
     */
    public function testCallRoute($handler, $params, $expectedResponseBody, $expectedResponseHeaders,
                                  $expectException) {
        if ($expectException) {
            $this->setExpectedException('\Slova\Core\Exception');
        }

        $fc = new FrontController($this->app);
        $fc->serve($handler, $params);
        $response = $this->app->getResponse();

        if (!$expectException) {
            $this->assertEquals($expectedResponseBody, $response->getContent());
            $this->assertEquals($expectedResponseHeaders, $response->getHeaders());
        }
    }

    public function callRouteDataProvider() {
        return [
            [
                ['\Slova\Core\FrontControllerTestTestController', 'noParamsAction'],
                [],
                json_encode(['result']),
                ['Content-Type' => 'application/json'],
                false
            ],
            [
                ['\Slova\Core\FrontControllerTestTestController', 'urlParamsAction'],
                ['param1' => 'value1', 'param2' => 'value2'],
                json_encode(['value1', 'value2']),
                ['Content-Type' => 'application/json'],
                false
            ],
            [
                ['\Slova\Core\FrontControllerTestTestController', 'urlParamsActionWithDefault'],
                ['param1' => 'value1'],
                json_encode(['value1', 'value2']),
                ['Content-Type' => 'application/json'],
                false
            ],
            [
                ['\Slova\Core\FrontControllerTestTestController', 'urlParamsActionNoRequired'],
                ['param1' => 'value1'],
                json_encode(['value1', 'value2']),
                ['Content-Type' => 'application/json'],
                true
            ],
            [
                ['\Slova\Core\FrontControllerTestTestController', 'objectParamsAction'],
                ['param1' => 'value1'],
                json_encode([true]),
                ['Content-Type' => 'application/json'],
                false
            ],
            [
                ['\Slova\Core\FrontControllerTestTestController', 'objectParamsUrlParamsAction'],
                ['param1' => 'value1'],
                json_encode([true, 'value1', 10]),
                ['Content-Type' => 'application/json'],
                false
            ],
        ];
    }
}

class FrontControllerTestTestController {

    public function noParamsAction() {
        return ['result'];
    }

    public function urlParamsAction($param1, $param2) {
        return [$param1, $param2];
    }

    public function urlParamsActionWithDefault($param1, $param2 = 'value2') {
        return [$param1, $param2];
    }

    public function urlParamsActionNoRequired($param1, $param2) {
        return [$param1, $param2];
    }

    public function objectParamsAction(App $app, Request $request, Response $response, FrontController $controller) {
        return [is_a($app, 'Slova\Core\App') && is_a($request, 'Slova\Core\Request')
            && is_a($response, 'Slova\Core\Response') && is_a($controller, 'Slova\Core\FrontController')];
    }

    public function objectParamsUrlParamsAction(Request $request, $param1, $param2 = 10) {
        return [is_a($request,'Slova\Core\Request'), $param1, $param2];
    }
}
