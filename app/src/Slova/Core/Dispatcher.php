<?php

namespace Slova\Core;

class Dispatcher
{
    /**
     * Param being used during url rewrite
     */
    const PATH_GET_PARAM = '__url_path';

    /**
     * @var App
     */
    protected $app;

    /**
     * @var FrontController
     */
    protected $frontController;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @param FrontController $frontController
     */
    public function setFrontController(FrontController $frontController)
    {
        $this->frontController = $frontController;
    }

    /**
     * @return FrontController
     */
    public function getFrontController()
    {
        if (!$this->frontController) {
            $this->frontController = new FrontController($this->app);
        }
        return $this->frontController;
    }


    /**
     * @param Router $router
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        if (!$this->router) {
            $this->router = new Router($this->app);
        }
        return $this->router;
    }

    /**
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Dispatches the request.
     */
    public function dispatch()
    {
        try {
            list($route, $params) = $this->getRouter()->findRoute(
                $this->app->getRequest()->get(static::PATH_GET_PARAM)
            );

            if (!$route) {
                $this->getFrontController()->notFound();
                return;
            }

            $this->app->getRequest()->setParams($params);

            $handler = $this->app->config['routes'][$route]['handler'];
            $this->getFrontController()->serve($handler);

        } catch (\Exception $e) {
            $this->getFrontController()->exception($e);
        }
    }
}
