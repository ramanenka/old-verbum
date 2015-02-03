<?php

namespace Slova\Core;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class App
 *
 * Main application class
 *
 * @package Slova\Core
 */
class App
{
    /**
     * Stores application config
     *
     * @var array
     */
    protected $config = array();

    /**
     * @var Event\Manager
     */
    protected $eventManager;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var FrontController
     */
    protected $frontController;

    /**
     * @var Di\Container
     */
    protected $di;

    /**
     * Prepares application to be executed
     *
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->config = $config;
        $this->defineGlobalConstants();
        $this->initDiContainer();
        $this->initEvents();
    }

    protected function defineGlobalConstants()
    {
        define('DS', DIRECTORY_SEPARATOR);
    }

    protected function initDiContainer()
    {
        $this->di = new Di\Container($this);
        $this->di->init();
    }

    protected function initEvents()
    {
        $events = require $this->getDir('config') . DS . 'events.php';
        foreach ($events as $eventName => $event) {
            foreach ($event as $observerName => $observerConfig) {
                $this->getEventManager()->addObserver($eventName, $observerConfig);
            }
        }
    }

    /**
     * @return Di\Container
     */
    public function getDi()
    {
        return $this->di;
    }

    /**
     * @return Event\Manager
     */
    public function getEventManager()
    {
        if (!$this->eventManager) {
            $this->eventManager = $this->di->get('event_manager');
        }

        return $this->eventManager;
    }


    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        if (!$this->request) {
            $this->request = new Request();
        }
        return $this->request;
    }

    public function getFrontController()
    {
        if (!$this->frontController) {
            $this->frontController = $this->di->get('front_controller');
            $this->frontController->init();
        }

        return $this->frontController;
    }

    /**
     * @param string $dirName
     * @return string
     */
    public function getDir($dirName = 'base')
    {
        if (isset($this->config['dir'][$dirName])) {
            return $this->config['dir'][$dirName];
        }

        return $this->config['dir']['base'] . DS . $dirName;
    }

    /**
     * Serves the agent request
     */
    public function serve()
    {
        $this->getFrontController()->dispatch();
    }
}
