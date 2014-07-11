<?php

namespace Slova\Core;


class Response {

    protected $content = '';

    protected $headers = [];

    public function getContent() {
        return $this->content;
    }

    public function getHeaders() {
        return $this->headers;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function setHeader($name, $value) {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * @param $content
     * @return $this
     */
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function send() {
        foreach ($this->headers as $name=>$value) {
            header("$name: $value");
        }

        echo $this->content;
    }
}
