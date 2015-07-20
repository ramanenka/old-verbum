<?php

namespace Verbum\Core;

class Request
{
    /**
     * Params array that were extracted from the URL
     *
     * @var array
     */
    protected $params = array();

    /**
     * List of request headers
     *
     * @var array
     */
    protected $headers = array();

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
     * Setts value of the specified param
     *
     * @param $name
     * @param $value
     * @return $this
     */
    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }

    /**
     * Returns the param value
     *
     * @param $name
     * @param mixed|null $default
     * @return mixed|null
     */
    public function getParam($name, $default = null)
    {
        return isset($this->params[$name]) ? $this->params[$name] : $default;
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

    /**
     * Set request headers
     *
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Return request headers
     *
     * @return array
     */
    public function getHeaders()
    {
        if (!$this->headers) {
            $this->headers = apache_request_headers();
        }

        return $this->headers;
    }

    /**
     * Get request header value
     *
     * @param string $name
     * @param mixed $default
     * @return string
     */
    public function getHeader($name, $default = null)
    {
        return isset($this->getHeaders()[$name]) ? $this->getHeaders()[$name] : $default;
    }
}
