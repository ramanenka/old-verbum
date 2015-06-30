<?php

namespace Verbum\Dict;

class MainTemplateTest extends \PHPUnit_Framework_TestCase
{

    public function staticsTypeList()
    {
        return [['getJSFilesList'], ['getCSSFilesList']];
    }

    /**
     * @dataProvider staticsTypeList
     */
    public function testStaticsFilesList($type)
    {
        $template = new MainTemplate();

        $this->assertInternalType(
            'array',
            $template->$type(),
            "$type() must return an array of strings"
        );

        $this->assertTrue(array_reduce($template->$type(), function ($carry, $item) {
            return $carry && strpos($item, 'v=') > 0;
        }, true), 'All statics should be boost-able.');
    }

    public function testRender()
    {
        $template = new MainTemplate();
        $template->setTemplate('dev/tests/unit/Verbum/Dict/main-template-test.phtml');

        $this->assertEquals(
            "This is the template for MainTemplateTest\n",
            $template->render(),
            'render() must include the template and return interpreted text'
        );
    }
}
