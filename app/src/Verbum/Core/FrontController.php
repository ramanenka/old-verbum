<?php

namespace Verbum\Core;

class FrontController
{
    /**
     * @var App
     */
    protected $app;

    /**
     * Class name of the default controller
     *
     * @var string
     */
    protected $defaultControllerClass = 'Verbum\Core\DefaultController';

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function serve($handler)
    {
        $actionResult = $this->callHandler($handler);
        $this->processActionResult($actionResult);
    }

    protected function processActionResult($actionResult)
    {
        if (is_array($actionResult)) {
            $this->processActionResultJSON($actionResult);
        }
    }

    protected function processActionResultJSON($actionResult)
    {
        $this->app->getResponse()
            ->setContent(json_encode($actionResult))
            ->setHeader('Content-Type', 'application/json');
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
        return new $class();
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
