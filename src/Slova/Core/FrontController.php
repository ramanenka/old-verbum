<?php

namespace Slova\Core;

use Symfony\Component\Routing\RouteCollection;

class FrontController
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var HttpKernel
     */
    protected $httpKernel;

    /**
     * @var RouteCollection
     */
    protected $routes;


    public function __construct(App $app, HttpKernel $httpKernel)
    {
        $this->app = $app;
        $this->httpKernel = $httpKernel;
    }

    public function init()
    {
        $this->routes = require $this->app->getDir('config') . DS . 'routes.php';
    }

    /**
     * @return RouteCollection
     */
    public function getRoutes()
    {
        return $this->routes;
    }


    public function dispatch()
    {
        $request = $this->app->getRequest()->createFromGlobals();
        $response = $this->httpKernel->handle($request);
        $response->send();
    }
}
