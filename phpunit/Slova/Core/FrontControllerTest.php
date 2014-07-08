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
}
