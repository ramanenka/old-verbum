<?php

namespace Slova\Core;

class Autoloader
{
    protected $includePath;

    public function __construct()
    {
        $this->includePath = explode(PATH_SEPARATOR, get_include_path());
    }

    public function doIt($className)
    {
        $fileName = str_replace('\\', '/', $className) . '.php';
        foreach ($this->includePath as $path) {
            if (file_exists($path . '/' . $fileName)) {
                require_once $path . '/' . $fileName;
            }
        }
    }
}
