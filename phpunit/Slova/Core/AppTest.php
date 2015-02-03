<?php

namespace Slova\Core;

class AppTest extends \PHPUnit_Framework_TestCase
{

    public function testDefineGlobalConstants()
    {
        $this->assertTrue(defined('DS'), 'DS constant must be defined');
    }
}
