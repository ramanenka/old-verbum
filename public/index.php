<?php

chdir('../');

require_once 'vendor/autoload.php';

$config = require 'app/config/main.php';
$config['dir']['base'] = getcwd();
$app = new Slova\Core\App($config);
$app->serve();
