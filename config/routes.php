<?php
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$routes = [
    'default' => [
        'path'     => '/',
        'defaults' => [
            '_controller' => '\Slova\Dict\IndexController::indexAction',
        ],
    ],
    'test' => [
        'path' => '/test/{param1}/{param2}',
        'defaults' => [
            '_controller' => '\Slova\Dict\IndexController::testAction',
        ],
        'requirements' => [
            'param1' => 'a|b',
            'param2' => '\d+',
        ],
    ],
    'panel-index' => [
        'path' => '/panel',
        'defaults' => [
            '_controller' => '\Slova\Panel\IndexController::indexAction',
        ],
    ],
];

$collection = new RouteCollection();

foreach ($routes as $route => $config) {
    $collection->add(
        $route,
        new Route(
            $config['path'],
            $config['defaults'],
            isset($config['requirements']) ? $config['requirements'] : array(),
            isset($config['options']) ? $config['options'] : array(),
            isset($config['host']) ? $config['host'] : '',
            isset($config['schemes']) ? $config['schemes'] : array(),
            isset($config['methods']) ? $config['methods'] : array(),
            isset($config['condition']) ? $config['condition'] : ''
        )
    );
}
return $collection;
