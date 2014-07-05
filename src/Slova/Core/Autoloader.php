<?php

namespace Slova\Core;

class Autoloader {
    public function doIt($className) {
        $fileName = str_replace('\\', '/', $className) . '.php';
        require_once $fileName;
    }
}
