<?php

namespace Slova\Core;

require_once 'Autoloader.php';

/**
 * Class App
 *
 * Main application class
 *
 * @package Slova\Core
 */
class App {

    /**
     * Stores application config
     *
     * @var array
     */
    protected $config = array();

    /**
     * Prepares application to be executed
     *
     * @param array $config
     */
    public function __construct($config = array()) {
        $this->setIncludePath();
        $this->registerAutoloader();
    }

    /**
     * Adds src directory to the include path
     */
    protected function setIncludePath() {
        set_include_path(implode(PATH_SEPARATOR,
            array_merge(explode(PATH_SEPARATOR, get_include_path()), [BASE_PATH. '/src'])));
    }

    /**
     * Registers application autoloader
     *
     * @throws Exception
     */
    protected function registerAutoloader() {
        if (!spl_autoload_register(array(new Autoloader(), 'doIt'))) {
            throw new Exception("Failed to register autoloader.");
        }
    }

    /**
     * Serves the agent request
     */
    public function serve() {
        //init request
        //dispatch
        //send response
    }
}
