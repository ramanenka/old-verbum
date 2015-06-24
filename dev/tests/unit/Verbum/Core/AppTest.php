<?php

namespace Verbum\Core;

class AppTest extends \PHPUnit_Framework_TestCase
{
    public function testDefineGlobalConstants()
    {
        $this->assertTrue(defined('DS'), 'DS constant must be defined');
    }

    public function testServe()
    {
        /** @var App $app */
        $app = $this->getMockBuilder('Verbum\Core\App')
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $dispatcherMock = $this->getMockBuilder('\Verbum\Core\Dispatcher')
            ->disableOriginalConstructor()
            ->getMock();
        $dispatcherMock->expects($this->once())
            ->method('dispatch')
            ->with();
        $app->setDispatcher($dispatcherMock);
        $this->assertSame($dispatcherMock, $app->getDispatcher());

        $responseMock = $this->getMockBuilder('\Verbum\Core\Response')
            ->disableOriginalConstructor()
            ->getMock();
        $responseMock->expects($this->once())
            ->method('send')
            ->with();
        $app->setResponse($responseMock);

        $app->serve();
    }

    public function testSetGetElastic()
    {
        /** @var App $app */
        $app = $this->getMockBuilder('Verbum\Core\App')
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $app->config = ['elastic' => ['connection' => []]];
        $this->assertInstanceOf('\Elastica\Client', $app->getElastic());

        $clientMock = $this->getMock('\Elastica\Client', null, [], '', false);
        $app->setElastic($clientMock);
        $this->assertSame($clientMock, $app->getElastic());
    }

    public function testSetGetContainer()
    {
        /** @var App $app */
        $app = $this->getMockBuilder('Verbum\Core\App')
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertInstanceOf('\Verbum\Core\DI\Container', $app->getContainer());

        $containerMock = $this->getMock('\Verbum\Core\DI\Container', null, [], '', false);
        $app->setContainer($containerMock);
        $this->assertSame($containerMock, $app->getContainer());
    }
}
