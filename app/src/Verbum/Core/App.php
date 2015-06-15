<?php

namespace Verbum\Core;

use Elastica\Client;
use Verbum\Core\DI\Container;

/**
 * Class App
 *
 * Main application class
 *
 * @package Verbum\Core
 */
class App
{
    /**
     * Stores application config
     *
     * @var array
     */
    public $config = array();

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var
     */
    protected $container;

    /**
     * @var Client
     */
    protected $elastic;

    /**
     * Prepares application to be executed
     *
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->config = $config;
        $this->defineGlobalConstants();
        $this->initDIContainer();
    }

    protected function defineGlobalConstants()
    {
        define('DS', DIRECTORY_SEPARATOR);
    }

    protected function initDIContainer()
    {
        $container = $this->getContainer();
        $container->set('app', $this);
        $container->set('container', $container);
        $container->set('elastic', $this->getElastic());
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        if (!$this->container) {
            $this->container = new Container();
        }
        return $this->container;
    }

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return Client
     */
    public function getElastic()
    {
        if (!$this->elastic) {
            $this->elastic = new Client($this->config['elastic']['connection']);
        }
        return $this->elastic;
    }

    /**
     * @param Client $elastic
     */
    public function setElastic($elastic)
    {
        $this->elastic = $elastic;
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher($dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return Dispatcher
     */
    public function getDispatcher()
    {
        if (!$this->dispatcher) {
            $this->dispatcher = new Dispatcher($this);
        }

        return $this->dispatcher;
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

    /**
     * @param Response $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        if (!$this->response) {
            $this->response = new Response();
        }
        return $this->response;
    }

    /**
     * Serves the agent request
     */
    public function serve()
    {
        $this->getDispatcher()->dispatch();
        $this->getResponse()->send();
    }
}
