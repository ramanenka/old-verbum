<?php

namespace Slova\Core;


class Request {

    public function get($name, $default = null) {
        return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
    }
} 
