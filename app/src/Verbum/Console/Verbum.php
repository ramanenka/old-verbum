<?php

namespace Verbum\Console;

use Symfony\Component\Console\Application;
use Verbum\Core\DI\Container;

class Verbum extends Application
{
    protected $container;

    public function __construct($name = 'verbum', $version = '1.0')
    {
        parent::__construct($name, $version);
    }

    /**
     * @param Container $container
     * @inject container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        $this->add($this->container->get('\Verbum\Console\IndexCommand'));
    }
}
