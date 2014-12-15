<?php
/**
 * Created by PhpStorm.
 * User: vad
 * Date: 7/6/14
 * Time: 10:54 AM
 */

namespace Slova\Core;


class RouterTest extends \PHPUnit_Framework_TestCase {

    protected $routes = [
        'default' => [
            'handler' => [],
            'path' => '',
            'priority' => 100
        ],
        'one-level' => [
            'handler' => [],
            'path' => 'one-level-path',
            'priority' => 200
        ],
        'one-level-param' => [
            'handler' => [],
            'path' => ':param1',
            'priority' => 150
        ],
        'two-level' => [
            'handler' => [],
            'path' => 'level-one/level-two',
            'priority' => 300
        ],
        'two-level-param' => [
            'handler' => [],
            'path' => ':param1/:param2',
            'priority' => 250
        ],
        'multi-level-param' => [
            'handler' => [],
            'path' => 'param1/:param1/param2/:param2/param3/:param3',
            'priority' => 300
        ],
        'restricted-by-http-method' => [
            'handler'  => [],
            'path'     => 'post',
            'priority' => 400,
            'method'   => 'POST',
        ],
    ];

    /**
     * @dataProvider findRouteDataProvider
     */
    public function testFindRoute($path, $expected) {
        $app = $this->buildAppMock();
        $router = new Router($app);
        $this->assertEquals($expected, $router->findRoute($path));
    }

    public function findRouteDataProvider() {
        return [
            ['non/existing/path', false],
            ['', ['name' => 'default', 'params' => []]],
            ['one-level-path', ['name' => 'one-level', 'params' => []]],
            ['param1value', ['name' => 'one-level-param', 'params' =>['param1' => 'param1value']]],
            ['level-one/level-two', ['name' => 'two-level', 'params' => []]],
            ['param1value/param2value', [
                'name' => 'two-level-param',
                'params' => [
                    'param1' => 'param1value',
                    'param2' => 'param2value',
                ]
            ]],
            ['param1/value1/param2/value2/param3/value3', [
                'name' => 'multi-level-param',
                'params' => [
                    'param1' => 'value1',
                    'param2' => 'value2',
                    'param3' => 'value3',
                ]
            ]],
            ['param1/Вінсэнт/param2/Дунін/param3/Марцінкевіч', [
                'name' => 'multi-level-param',
                'params' => [
                    'param1' => 'Вінсэнт',
                    'param2' => 'Дунін',
                    'param3' => 'Марцінкевіч',
                ]
            ]],
        ];
    }

    public function testFindRouteWithHTTPMethodRestriction() {
        $app = $this->buildAppMock();
        $request = $this->getMock('\Slova\Core\Request', ['getMethod']);
        $request->method('getMethod')
            ->willReturn('POST');
        $app->setRequest($request);

        $router = new Router($app);

        $this->assertEquals(
            ['name' => 'restricted-by-http-method', 'params' => []],
            $router->findRoute('post')
        );
    }

    /**
     * Build app mock
     *
     * @return App
     */
    protected function buildAppMock() {
        $mock = $this->getMockBuilder('\Slova\Core\App')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $mock->config = ['routes' => $this->routes];

        return $mock;
    }
}
