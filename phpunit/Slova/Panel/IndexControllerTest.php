<?php

namespace Slova\Panel;

use Slova\Core\Response;

class IndexControllerTest extends \PHPUnit_Framework_TestCase
{
    public function indexActionTest()
    {
        $controller = new IndexController();
        $response = new Response();

        $controller->indexAction($response);

        $this->assertNotEmpty($response->getContent());
        $this->assertNotEmpty($response->getHeaders());
    }
}
