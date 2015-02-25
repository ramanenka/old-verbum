<?php

namespace Slova\Core;

class AppTest extends \PHPUnit_Framework_TestCase
{

    public function testDefineGlobalConstants()
    {
        $this->assertTrue(defined('DS'), 'DS constant must be defined');
    }

    public function testServe()
    {
        /** @var App $app */
        $app = $this->getMockBuilder('Slova\Core\App')
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $dispatcherMock = $this->getMockBuilder('\Slova\Core\Dispatcher')
            ->disableOriginalConstructor()
            ->getMock();
        $dispatcherMock->expects($this->once())
            ->method('dispatch')
            ->with();
        $app->setDispatcher($dispatcherMock);

        $responseMock = $this->getMockBuilder('\Slova\Core\Response')
            ->disableOriginalConstructor()
            ->getMock();
        $responseMock->expects($this->once())
            ->method('send')
            ->with();
        $app->setResponse($responseMock);

        $app->serve();
    }
}
