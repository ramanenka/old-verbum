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

        $responseMock = $this->getMockBuilder('\Verbum\Core\Response')
            ->disableOriginalConstructor()
            ->getMock();
        $responseMock->expects($this->once())
            ->method('send')
            ->with();
        $app->setResponse($responseMock);

        $app->serve();
    }
}
