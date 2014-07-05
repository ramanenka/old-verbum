<?php

define('BASE_PATH', dirname(__DIR__). '/');

require_once BASE_PATH.'src/Slova/Core/App.php';

$app = new Slova\Core\App(require BASE_PATH.'config/main.php');
