<?php

namespace Slova\Core;

class AppTest extends \PHPUnit_Framework_TestCase {

    public function testIncludePath() {
        $paths = explode(PATH_SEPARATOR, get_include_path());
        $this->assertNotFalse(array_search(BASE_PATH . '/src', $paths), 'App should add src dir to include path');
    }

    public function testRegisterAutoloader() {
        $found = false;
        foreach (spl_autoload_functions() as $autoloader) {
            if (is_array($autoloader) && $autoloader[0] instanceof Autoloader) {
                $found = true;
            }
        }

        $this->assertTrue($found, 'App class should register an autoloader');
    }

    public function testServe() {
        /** @var App $app */
        $app = $this->getMockBuilder('Slova\Core\App')
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $app->serve();
    }

}
