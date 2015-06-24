<?php


namespace Verbum\Console;

use Symfony\Component\Console\Command\Command;
use Verbum\Core\DI\Container;

class VerbumTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $verbum = new Verbum();
        $this->assertEquals('verbum', $verbum->getName());
        $this->assertEquals('1.0', $verbum->getVersion());
    }

    public function testSetContainer()
    {
        $container = $this->getMock('\Verbum\Core\DI\Container', ['get']);
        $container->expects($this->exactly(1))
            ->method('get')
            ->withConsecutive(['\Verbum\Console\IndexCommand'])
            ->willReturn(new Command('test_command'));

        $verbum = new Verbum();
        $verbum->setContainer($container);
    }
}
