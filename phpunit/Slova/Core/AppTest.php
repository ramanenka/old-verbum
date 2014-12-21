<?php

namespace Slova\Core;

class AppTest extends \PHPUnit_Framework_TestCase
{
    public function testIncludePath()
    {
        global $app;
        $paths = explode(PATH_SEPARATOR, get_include_path());
        $this->assertNotFalse(
            array_search($app->config['dir']['base'] . '/src', $paths),
            'App should add src dir to include path'
        );
    }

    public function testRegisterAutoloader()
    {
        $found = false;
        foreach (spl_autoload_functions() as $autoloader) {
            if (is_array($autoloader) && $autoloader[0] instanceof Autoloader) {
                $found = true;
            }
        }

        $this->assertTrue($found, 'App class should register an autoloader');
    }

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
