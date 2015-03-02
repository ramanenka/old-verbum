<?php

namespace Slova\Core;

class Response
{
    /**
     * HTTP response code
     *
     * @var int
     */
    protected $code = 200;

    protected $content = '';

    protected $headers = [];

    public function getContent()
    {
        return $this->content;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * @param $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Returns the HTTP response code
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Setts the HTTP response code
     *
     * @param int $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function send()
    {
        http_response_code($this->getCode());

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        echo $this->content;
    }
}
