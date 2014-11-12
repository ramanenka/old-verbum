<?php

namespace Slova\Core;


class RequestTest extends \PHPUnit_Framework_TestCase {

    public function tearDown() {
        unset($_SERVER['REQUEST_METHOD']);
    }

    public function testGetMethod() {
        $request = new Request();
        unset($_SERVER['REQUEST_METHOD']);
        $this->assertNull($request->getMethod());

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals('GET', $request->getMethod());
    }
}
