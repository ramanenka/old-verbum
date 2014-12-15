<?php

namespace Slova\Core;

/**
 * Class Router
 *
 * Looks for routes based on url path.
 *
 * @package Slova\Core
 */
class Router {

    /**
     * @var App
     */
    protected $app;

    /**
     * @param App $app
     */
    public function __construct(App $app) {
        $this->app = $app;
    }

    /**
     * Returns route name and name-value params array
     *
     * @param $path
     * @return array|bool
     * @throws Exception
     */
    public function findRoute($path) {
        $routes = $this->app->config['routes'];
        uasort($routes, function($a, $b) {
            return $a['priority'] < $b['priority'];
        });

        foreach ($routes as $routeName => $route) {
            if (isset($route['method']) && $route['method'] != $this->app->getRequest()->getMethod()) {
                continue;
            }
            list($regexp, $names) = $this->prepareRegExp($route['path']);
            $matches = [];
            $matchResult = preg_match($regexp, $path, $matches);
            if ($matchResult === false) {
                throw new Exception("Error when preg_match");
            } elseif ($matchResult) {
                return ['name' => $routeName, 'params' => $this->prepareParams($names, $matches)];
            }
        }

        return false;
    }

    /**
     * Prepares the regular expression to match url path to the current route.
     * Returns prepared regular expression and array of url param names.
     *
     * @param $path
     * @return array
     * @throws Exception
     */
    protected function prepareRegExp($path) {
        $nameMatches = [];
        if (preg_match_all('/:([a-z0-9_-]*)/', $path, $nameMatches) === false) {
            throw new Exception("Error when prag_match_all");
        }
        $names = $nameMatches[1];
        $path = str_replace('/', '\/', $path);
        $path = str_replace($nameMatches[0], '([^\/]+)', $path);
        return ['/^'.$path.'$/', $names];
    }

    /**
     * Retrieves param values from the matches array.
     *
     * @param $names
     * @param $matches
     * @return array
     */
    protected function prepareParams($names, $matches) {
        $result = [];
        for ($i = 0; $i < count($names); $i++) {
            $result[$names[$i]] = $matches[$i+1];
        }
        return $result;
    }
} 
