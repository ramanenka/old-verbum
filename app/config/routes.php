<?php

$config['routes'] = [
    'default' => [
        'handler' => ['\Verbum\Dict\IndexController', 'indexAction'],
        'path' => '',
        'priority' => 100,
    ],
    'search_direct' => [
        'handler' => ['\Verbum\Dict\IndexController', 'searchAction'],
        'path' => ':q',
        'priority' => 50,
    ],
    'typeahead' => [
        'handler' => ['\Verbum\Dict\IndexController', 'typeaheadAction'],
        'path' => '_typeahead/:q',
        'priority' => 100,
    ],
    'panel-index' => [
        'handler' => ['\Verbum\Panel\IndexController', 'indexAction'],
        'path' => 'panel',
        'priority' => 200
    ],
];
