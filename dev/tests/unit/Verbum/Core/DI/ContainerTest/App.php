<?php

namespace Verbum\Core\DI\ContainerTest;

class App
{

    /**
     * @inject Verbum\Core\DI\ContainerTest\Request
     */
    public function setRequest($request)
    {

    }

    /**
     * @inject Verbum\Core\DI\ContainerTest\Request
     */
    public function getSomething()
    {

    }

    /**
     * @inject response
     */
    public function setResponse($response)
    {

    }

    public function setController($controller)
    {

    }
}
