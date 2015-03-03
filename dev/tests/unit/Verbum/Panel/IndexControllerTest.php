<?php

namespace Verbum\Panel;

use Verbum\Core\Response;

class IndexControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testIndexActionTest()
    {
        $controller = new IndexController();
        $response = new Response();

        $controller->indexAction($response);

        $this->assertNotEmpty($response->getContent());
        $this->assertNotEmpty($response->getHeaders());
    }
}
