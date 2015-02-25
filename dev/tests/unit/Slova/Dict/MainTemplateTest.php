<?php

namespace Slova\Dict;

class MainTemplateTest extends \PHPUnit_Framework_TestCase
{

    public function testGetJSFilesList()
    {
        $template = new MainTemplate();

        $this->assertInternalType(
            'array',
            $template->getJSFilesList(),
            'getJSFilesList() must return an array of strings'
        );
    }

    public function testRender()
    {
        $template = new MainTemplate();
        $template->setTemplate('dev/tests/unit/Slova/Dict/main-template-test.phtml');

        $this->assertEquals(
            "This is the template for MainTemplateTest\n",
            $template->render(),
            'render() must include the template and return interpreted text'
        );
    }
}
