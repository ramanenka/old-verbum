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
];
