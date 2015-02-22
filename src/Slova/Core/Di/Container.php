<?php

namespace Slova\Core\Di;

use Slova\Core;

class Container
{

    protected $di = [];

    /**
     * @var \Slova\Core\App
     */
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function init()
    {
        $this->di = require $this->app->getDir('config') . DS . 'di.php';
    }

    public function get($name)
    {
        if (!isset($this->di[$name])) {
            return null;
        }

        if (!isset($this->di[$name]['instance'])) {
            $config = $this->di[$name];
            if (isset($config['factory_class'])) {
                $factory = new $config['factory_class']($this->app);
                if (isset($config['factory_method'])) {
                    $factoryMethod = $config['factory_method'];
                } else {
                    $factoryMethod = 'build';
                }
                $this->di[$name]['instance'] = $factory->$factoryMethod();
            } elseif (isset($config['class'])) {
                $params = isset($config['params']) ? $config['params'] : [];
                foreach ($params as $key => $param) {
                    if (strpos($param, '_app') === 0) {
                        $actions = explode('::', $param);
                        $paramValue = $this->app;
                        for ($i = 1; $i < count($actions); $i++) {
                            $paramValue = $paramValue->$actions[$i]();
                        }
                        $params[$key] = $paramValue;
                    } elseif (strpos($param, '_class::') === 0) {
                        $className = str_replace('_class::', '', $param);
                        $params[$key] = new $className();
                    } else {
                        $params[$key] = $this->get($param);
                    }
                }
                $this->di[$name]['instance'] = new $config['class'](...$params);
            }
        }



        return $this->di[$name]['instance'];
    }
}
