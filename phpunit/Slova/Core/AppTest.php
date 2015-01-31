<?php

namespace Slova\Core;

class AppTest extends \PHPUnit_Framework_TestCase
{

    protected $resetErrorHandler = false;

    protected function tearDown()
    {
        if ($this->resetErrorHandler) {
            restore_error_handler();
            $this->resetErrorHandler = false;
        }
    }

    public function testDefineGlobalConstants()
    {
        $this->assertTrue(defined('DS'), 'DS constant must be defined');
    }

    public function testRegisterErrorHandler()
    {
        $this->resetErrorHandler = true;
        // trick to retrieve the current error handler
        $errorHandlerConfig = set_error_handler(
            function () {

            }
        );
        $this->assertInstanceOf('\Symfony\Component\Debug\ErrorHandler', $errorHandlerConfig[0]);
        $this->assertEquals('handleError', $errorHandlerConfig[1]);
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
