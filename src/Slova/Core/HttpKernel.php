<?php

namespace Slova\Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpKernel
{

    protected $eventManager;

    protected $requestStack;

    public function __construct(Event\ManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    public function handle(Request $request, $catch = true)
    {
        try {
            return $this->handleRaw($request);
        } catch (\Exception $e) {
            if (false === $catch) {
                $this->finishRequest($request);
                throw $e;
            }
            return $this->handleException($e, $request);
        }
    }

    protected function handleRaw(Request $request)
    {
        $this->eventManager->dispatch('kernel_request', ['request' => $request]);

        if (false === $controller = $this->getController($request)) {
            throw new \Exception(
                sprintf(
                    'Unable to find the controller for path "%s". The route is wrongly configured.',
                    $request->getPathInfo()
                )
            );
        }

        $this->eventManager->dispatch('kernel_controller', ['controller' => $controller, 'request' => $request]);


        // controller arguments
        $arguments = $this->getArguments($request, $controller);

        // call controller
        $response = call_user_func_array($controller, $arguments);

        // view
        if (!$response instanceof Response) {
            $this->eventManager->dispatch('kernel_view', ['request' => $request, 'response' => $response]);
        }

        return $this->filterResponse($response, $request);
    }

    protected function filterResponse(Response $response, Request $request)
    {
        $this->eventManager->dispatch('kernel_response', ['request' => $request, 'response' => $response]);

        $this->finishRequest($request);

        return $response;
    }

    private function finishRequest(Request $request)
    {
        $this->eventManager->dispatch('kernel_finish_request', ['request' => $request]);
    }

    protected function handleException(\Exception $e, $request)
    {
        $this->eventManager->dispatch('kernel_exception', ['exception' => $e, 'request' => $request]);

        //toDo
    }




    // toDo check the next functions maybe we can move it in separate class
    public function getController(Request $request)
    {
        if (!$controller = $request->attributes->get('_controller')) {
            return false;
        }

        if (is_array($controller)) {
            return $controller;
        }

        if (is_object($controller)) {
            if (method_exists($controller, '__invoke')) {
                return $controller;
            }

            throw new \InvalidArgumentException(
                sprintf(
                    'Controller "%s" for URI "%s" is not callable.',
                    get_class($controller),
                    $request->getPathInfo()
                )
            );
        }

        if (false === strpos($controller, ':')) {
            if (method_exists($controller, '__invoke')) {
                return $this->instantiateController($controller);
            } elseif (function_exists($controller)) {
                return $controller;
            }
        }

        $callable = $this->createController($controller);

        if (!is_callable($callable)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Controller "%s" for URI "%s" is not callable.',
                    $controller,
                    $request->getPathInfo()
                )
            );
        }

        return $callable;
    }

    public function getArguments(Request $request, $controller)
    {
        if (is_array($controller)) {
            $r = new \ReflectionMethod($controller[0], $controller[1]);
        } elseif (is_object($controller) && !$controller instanceof \Closure) {
            $r = new \ReflectionObject($controller);
            $r = $r->getMethod('__invoke');
        } else {
            $r = new \ReflectionFunction($controller);
        }

        return $this->doGetArguments($request, $controller, $r->getParameters());
    }

    protected function doGetArguments(Request $request, $controller, array $parameters)
    {
        $attributes = $request->attributes->all();
        $arguments = array();
        foreach ($parameters as $param) {
            if (array_key_exists($param->name, $attributes)) {
                $arguments[] = $attributes[$param->name];
            } elseif ($param->getClass() && $param->getClass()->isInstance($request)) {
                $arguments[] = $request;
            } elseif ($param->isDefaultValueAvailable()) {
                $arguments[] = $param->getDefaultValue();
            } else {
                if (is_array($controller)) {
                    $repr = sprintf('%s::%s()', get_class($controller[0]), $controller[1]);
                } elseif (is_object($controller)) {
                    $repr = get_class($controller);
                } else {
                    $repr = $controller;
                }

                throw new \RuntimeException(
                    sprintf(
                        'Controller "%s" requires that you provide a value for the "$%s" argument.',
                        $repr,
                        $param->name
                    )
                );
            }
        }

        return $arguments;
    }

    protected function createController($controller)
    {
        if (false === strpos($controller, '::')) {
            throw new \InvalidArgumentException(sprintf('Unable to find controller "%s".', $controller));
        }

        list($class, $method) = explode('::', $controller, 2);

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        return array($this->instantiateController($class), $method);
    }


    protected function instantiateController($class)
    {
        return new $class();
    }
}
