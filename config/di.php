<?php

$di = [
    'route_observer' => [
        'class'  => '\Slova\Core\Route\Observer',
        'params' => ['symfony_url_matcher'],
    ],
    'symfony_url_matcher' => [
        'class'  => '\Symfony\Component\Routing\Matcher\UrlMatcher',
        'params' => ['_app::getFrontController::getRoutes', '_class::\Symfony\Component\Routing\RequestContext']
    ],
    'event_manager' => [
        'class'  => '\Slova\Core\Event\Manager',
        'params' => ['_app'],
    ],
    'http_kernel' => [
        'class'  => '\Slova\Core\HttpKernel',
        'params' => ['event_manager']
    ],
    'front_controller' => [
        'class'  => '\Slova\Core\FrontController',
        'params' => ['_app', 'http_kernel'],
    ],
];

return $di;
