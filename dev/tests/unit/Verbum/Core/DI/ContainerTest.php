<?php

namespace Verbum\Core\DI;

/**
 * Class ContainerTest
 * @package Verbum\Core\DI
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Only object can be set to DI container. string is given
     */
    public function testSetException()
    {
        $container = new Container();
        $container->set('test', 'test');
    }

    public function testSetGet()
    {
        $container = $this->getMock('Verbum\Core\DI\Container', ['instantiate']);
        $container->expects($this->never())
            ->method('instantiate');

        $testObj = new \stdClass();
        $container->set('test_std', $testObj);

        $this->assertSame($testObj, $container->get('test_std'));
    }

    public function testInstantiate()
    {
        $container = $this->getMock('Verbum\Core\DI\Container', ['createObject']);

        $testAppMock = $this->getMock(
            'Verbum\Core\DI\ContainerTest\App',
            ['setRequest', 'getSomething', 'setResponse', 'setController']
        );
        $testAppMock->expects($this->once())
            ->method('setRequest')
            ->with($this->isInstanceOf('Verbum\Core\DI\ContainerTest\Request'));
        $testAppMock->expects($this->never())
            ->method('getSomething');
        $testAppMock->expects($this->once())
            ->method('setResponse')
            ->with($this->isInstanceOf('Verbum\Core\DI\ContainerTest\Response'));
        $testAppMock->expects($this->never())
            ->method('setController');

        $request = new ContainerTest\Request();
        $container->expects($this->exactly(2))
            ->method('createObject')
            ->willReturnMap(
                [
                    ['Verbum\Core\DI\ContainerTest\App', $testAppMock],
                    ['Verbum\Core\DI\ContainerTest\Request', $request],
                ]
            );
        $response = new ContainerTest\Response();
        $container->set('response', $response);

        $this->assertSame($testAppMock, $container->get('Verbum\Core\DI\ContainerTest\App'));
    }

    public function testInstantiateChainClosure()
    {
        $chain = [
            'Verbum\Core\DI\ContainerTest\AppChain',
            'Verbum\Core\DI\ContainerTest\Controller',
            'Verbum\Core\DI\ContainerTest\Block',
        ];
        $chainText = implode('->', $chain);
        $this->setExpectedException(
            'Exception',
            sprintf(
                'Cannot instantiate Verbum\Core\DI\ContainerTest\AppChain. It depends on itself. Chain: %s',
                $chainText
            )
        );
        $container = new Container();
        $container->get('Verbum\Core\DI\ContainerTest\AppChain');
    }
}
