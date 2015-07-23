<?php

namespace Verbum\Core;

use Verbum\Core\DI\Container;

class FrontController
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var Container
     */
    protected $container;

    /**
     * Class name of the default controller
     *
     * @var string
     */
    protected $defaultControllerClass = 'Verbum\Core\DefaultController';

    /**
     * @param App $app
     * @inject app
     * @return $this
     */
    public function setApp($app)
    {
        $this->app = $app;
        return $this;
    }

    /**
     * @param Container $container
     * @inject container
     * @return $this
     */
    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }

    public function serve($handler)
    {
        $actionResult = $this->callHandler($handler);
        if ($actionResult) {
            $this->processActionResult($actionResult);
        }
    }

    protected function processActionResult($actionResult)
    {
        /** @var ActionResultProcessor $processor */
        $processor = $this->container->get($this->app->config['action_result_processor']);
        $processor->process($actionResult);
    }

    protected function callHandler($handler)
    {
        list ($class, $action) = $handler;
        $controller = $this->instantiateController($class);
        $arguments = $this->prepareActionArguments($controller, $action);
        return call_user_func_array([$controller, $action], $arguments);
    }

    protected function instantiateController($class)
    {
        return $this->container->get($class);
    }

    protected function prepareActionArguments($controller, $action)
    {
        $params = $this->app->getRequest()->getParams();

        $result = [];
        $objects = [$this->app, $this->app->getRequest(), $this->app->getResponse(), $this];

        $method = new \ReflectionMethod($controller, $action);
        foreach ($method->getParameters() as $param) {
            $object = false;
            if ($param->getClass()) {
                $objectsMatched = array_values(
                    array_filter(
                        $objects,
                        function ($object) use ($param) {
                            return is_a($object, $param->getClass()->getName());
                        }
                    )
                );
                if ($objectsMatched) {
                    $object = reset($objectsMatched);
                }
            }
            if ($object) {
                $result[] = $object;
            } elseif (isset($params[$param->getName()])) {
                $result[] = $params[$param->getName()];
            } elseif ($param->isDefaultValueAvailable()) {
                $result[] = $param->getDefaultValue();
            } else {
                throw new Exception("{$param->getName()} is required.");
            }
        }

        return $result;
    }

    public function notFound()
    {
        $this->callHandler([$this->defaultControllerClass, 'notFoundAction']);
    }

    public function exception(\Exception $e)
    {
        $this->app->getRequest()->setParam('exception', $e);
        $this->callHandler([$this->defaultControllerClass, 'exceptionAction']);
    }
}
