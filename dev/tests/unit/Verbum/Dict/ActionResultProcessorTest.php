<?php


namespace Verbum\Dict;

use Verbum\Core\Request;
use Verbum\Core\Response;

class ActionResultProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $request = new Request();
        $request->setHeaders([
            'Accept' => 'application/json'
        ]);

        $mock = $this->getMock('\Verbum\Dict\ActionResultProcessor', ['processJSON']);
        $mock->expects($this->exactly(2))
            ->method('processJSON')
            ->with('action-result');

        /** @var ActionResultProcessor $mock */
        $mock->setRequest($request);
        $mock->process('action-result');

        $request->setHeaders([
            'Accept' => 'application/json; charset=utf-8'
        ]);
        $mock->process('action-result');

        $mock = $this->getMock('\Verbum\Dict\ActionResultProcessor', ['processHTML']);
        $mock->expects($this->once())
            ->method('processHTML')
            ->with('action-result');

        $mock->setRequest($request);
        $request->setHeaders([
            'Accept' => '*/*'
        ]);
        $mock->process('action-result');
    }

    public function testProcessJSON()
    {
        $processor = new ActionResultProcessor();
        $response = new Response();
        $processor->setResponse($response);

        $actionResult = ['data1' => 'value1', 'data2' => 'value2'];
        $processor->processJSON($actionResult);
        $this->assertArrayHasKey('Content-Type', $response->getHeaders());
        $this->assertContains('application/json', $response->getHeaders()['Content-Type']);
        $this->assertEquals(json_encode($actionResult), $response->getContent());
    }

    public function testProcessHTML()
    {
        $actionResult = ['data1' => 'value1', 'data2' => 'value2'];

        $request = new Request();
        $request->setParam('q', 'query_word');

        $response = new Response();

        $template = $this->getMock('\Verbum\Dict\MainTemplate', ['render', 'setData']);
        $template->expects($this->once())
            ->method('render')
            ->willReturn('template rendered html');
        $template->expects($this->once())
            ->method('setData')
            ->with(['q' => 'query_word', 'results' => $actionResult])
            ->willReturn($template);

        $processor = new ActionResultProcessor();
        $processor->setTemplate($template);
        $processor->setResponse($response);
        $processor->setRequest($request);

        $actionResult = ['data1' => 'value1', 'data2' => 'value2'];
        $processor->processHTML($actionResult);

        $this->assertArrayHasKey('Content-Type', $response->getHeaders());
        $this->assertContains('text/html', $response->getHeaders()['Content-Type']);
        $this->assertEquals('template rendered html', $response->getContent());
    }
}
