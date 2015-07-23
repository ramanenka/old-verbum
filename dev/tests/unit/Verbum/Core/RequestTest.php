<?php

namespace Verbum\Core;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        unset($_SERVER['REQUEST_METHOD']);
    }

    public function testGetMethod()
    {
        $request = new Request();
        unset($_SERVER['REQUEST_METHOD']);
        $this->assertNull($request->getMethod());

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals('GET', $request->getMethod());
    }

    public function testParamsGetterSetter()
    {
        $params = [
            'key1' => 'value1',
        ];

        $request = new Request();
        $this->assertEmpty($request->getParams());

        $this->assertSame($request, $request->setParams($params));
        $this->assertEquals($params, $request->getParams());
    }

    public function testGetterSetterParam()
    {
        $request = new Request();
        $this->assertEmpty($request->getParams());
        $this->assertNull($request->getParam('param1'));
        $this->assertEquals('default', $request->getParam('param1', 'default'));

        $this->assertSame($request, $request->setParam('param1', 'value1'));
        $this->assertArrayHasKey('param1', $request->getParams());

        $this->assertEquals('value1', $request->getParam('param1'));
        $this->assertEquals('value1', $request->getParam('param1', 'value2'));
    }

    public function testHeaders()
    {
        $request = new Request();
        $headers = [
            'header1' => 'value1',
            'header2' => 'value2',
        ];

        $this->assertSame($request, $request->setHeaders($headers));
        $this->assertEquals($headers, $request->getHeaders());
        $this->assertEquals('value1', $request->getHeader('header1'));
        $this->assertEquals('value3', $request->getHeader('header3', 'value3'));
        $this->assertNull($request->getHeader('header3'));
    }
}
