<?php


namespace Verbum\Console;

use Symfony\Component\Console\Application;

class Verbum extends Application
{
    public function __construct($name = 'verbum', $version = '1.0')
    {
        parent::__construct($name, $version);
    }
}
