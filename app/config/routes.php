<?php

$config['routes'] = [
    'default' => [
        'handler' => ['\Verbum\Dict\IndexController', 'indexAction'],
        'path' => '',
        'priority' => 100,
    ],
    'test' => [
        'handler' => ['\Verbum\Dict\IndexController', 'testAction'],
        'path' => 'test',
        'priority' => 200
    ],
    'panel-index' => [
        'handler' => ['\Verbum\Panel\IndexController', 'indexAction'],
        'path' => 'panel',
        'priority' => 200
    ],
];
