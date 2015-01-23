<?php

require_once 'vendor/autoload.php';

$config = require 'config/main.php';
$config['dir']['base'] = getcwd();
$app = new Slova\Core\App($config);
