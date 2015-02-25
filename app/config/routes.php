<?php

$config['routes'] = [
    'default' => [
        'handler' => ['\Slova\Dict\IndexController', 'indexAction'],
        'path' => '',
        'priority' => 100,
    ],
    'test' => [
        'handler' => ['\Slova\Dict\IndexController', 'testAction'],
        'path' => 'test',
        'priority' => 200
    ],
    'panel-index' => [
        'handler' => ['\Slova\Panel\IndexController', 'indexAction'],
        'path' => 'panel',
        'priority' => 200
    ],
];
