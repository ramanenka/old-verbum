<?php

namespace Slova\Core;

class Request
{
    /**
     * Params array that were extracted from the URL
     *
     * @var array
     */
    protected $params = array();

    public function get($name, $default = null)
    {
        return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
    }

    /**
     * Returns an array of params
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Setts params array
     *
     * @param $params
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Return request method
     *
     * @return string|null
     */
    public function getMethod()
    {
        return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null;
    }
}
