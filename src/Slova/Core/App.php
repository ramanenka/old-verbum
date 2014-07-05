<?php

namespace Slova\Core;

require_once 'Autoloader.php';

class App {
    protected $config = array();

    public function __construct($config = array()) {
        $this->setIncludePath();
        $this->registerAutoloader();
    }

    protected function setIncludePath() {
        set_include_path(implode(PATH_SEPARATOR,
            array_merge(explode(PATH_SEPARATOR, get_include_path()), [BASE_PATH. 'src/'])));
    }

    protected function registerAutoloader() {
        if (!spl_autoload_register(array(new Autoloader(), 'doIt'))) {
            throw new Exception("Failed to register autoloader.");
        }
    }

    public function serve() {

    }
}
