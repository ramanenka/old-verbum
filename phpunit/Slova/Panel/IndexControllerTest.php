<?php

namespace Slova\Panel;

class IndexControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testIndexActionTest()
    {
        $controller = new IndexController();

        $response = $controller->indexAction();

        $this->assertNotEmpty($response->getContent());
    }
}
