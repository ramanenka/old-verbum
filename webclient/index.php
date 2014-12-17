<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = true;

$app->get('/', '\Slova\Controllers\Index::indexAction');

$app->run();