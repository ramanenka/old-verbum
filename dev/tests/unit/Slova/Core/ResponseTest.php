<?php

namespace Slova\Core;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testCodeGetterSetter()
    {
        $response = new Response();
        $this->assertEquals(200, $response->getCode());
        $this->assertSame($response, $response->setCode(404));
        $this->assertEquals(404, $response->getCode());
    }
}
