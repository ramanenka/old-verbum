<?php

chdir('../');

require_once 'src/Slova/Core/Autoloader.php';
require_once 'src/Slova/Core/App.php';

$config = require 'config/main.php';
$config['dir']['base'] = getcwd();
$app = new Slova\Core\App($config);
