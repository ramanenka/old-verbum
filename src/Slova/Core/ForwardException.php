<?php

namespace Slova\Core;

class ForwardException extends Exception {

    protected $name;

    protected $params;

    public function __construct($name, $params) {
        $this->name = $name;
        $this->params = $params;
    }

    public function getNewRouteName() {
        return $this->name;
    }

    public function getNewRouteParams() {
        return $this->params;
    }
} 
