<?php

namespace Slova\Core;


class FrontController {

    /**
     * @var App
     */
    protected $app;

    public function __construct(App $app) {
        $this->app = $app;
    }

    public function serve($handler, $params) {
        $actionResult = $this->callHandler($handler, $params);
        $this->processActionResult($actionResult);
    }

    protected function processActionResult($actionResult) {
        if (is_array($actionResult)) {
            $this->processActionResultJSON($actionResult);
        }
    }

    protected function processActionResultJSON($actionResult) {
        $this->app->getResponse()
            ->setContent(json_encode($actionResult))
            ->setHeader('Content-Type', 'application/json');
    }

    protected  function callHandler($handler, $params) {
        list ($class, $action) = $handler;
        $controller = $this->instantiateController($class);
        $arguments = $this->prepareActionArguments($controller, $action, $params);
        return call_user_func_array([$controller, $action], $arguments);
    }

    protected function instantiateController($class) {
        return new $class();
    }

    protected function prepareActionArguments($controller, $action, $params) {
        $result = [];
        $objects = [$this->app, $this->app->getRequest(), $this->app->getResponse(), $this];

        $method = new \ReflectionMethod($controller, $action);
        foreach ($method->getParameters() as $param) {
            $object = false;
            if ($param->getClass()) {
                $object = reset(array_filter($objects, function($object) use ($param) {
                    return is_a($object, $param->getClass()->getName());
                }));
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

    public function forward($routeName, $params = array()) {
        throw new ForwardException($routeName, $params);
    }

    public function noRoute() {

    }

    public function exception(Exception $e) {

    }
} 
