<?php

namespace Verbum\Core;

class DefaultControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testNotFoundAction()
    {
        $controller = new DefaultController();
        $response = new Response();

        $this->assertEquals(200, $response->getCode());

        $controller->notFoundAction($response);
        $this->assertEquals(404, $response->getCode());
    }

    public function testExceptionAction()
    {
        $controller = new DefaultController();
        $response = new Response();

        $this->assertEquals(200, $response->getCode());

        $exception = new Exception('some error message');
        $controller->exceptionAction($exception, $response);
        $this->assertEquals(500, $response->getCode());
        $this->assertContains('some error message', $response->getContent());
    }
}
