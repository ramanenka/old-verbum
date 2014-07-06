<?php
/**
 * Created by PhpStorm.
 * User: vad
 * Date: 7/6/14
 * Time: 10:54 AM
 */

namespace Slova\Core;


class RouterTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var Router
     */
    protected $router;

    protected function setUp()
    {
        parent::setUp();
        /** @var App $app */
        $app = $this->getMockBuilder('\Slova\Core\App')->disableOriginalConstructor()->getMock();
        $app->config = ['routes' => [
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
            ]
        ]];

        $this->router = new Router($app);
    }

    /**
     * @dataProvider findRouteDataProvider
     */
    public function testFindRoute($path, $expected) {
        $this->assertEquals($expected, $this->router->findRoute($path));
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
}
