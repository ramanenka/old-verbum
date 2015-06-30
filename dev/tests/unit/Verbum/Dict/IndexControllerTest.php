<?php

namespace Verbum\Panel;

use Elastica\Query;
use Elastica\Result;
use Verbum\Core\Response;
use Verbum\Dict\IndexController;

class IndexControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Verbum\Core\App
     */
    protected $app;

    protected function setUp()
    {
        $this->app = $this->getMockBuilder('Verbum\Core\App')
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->config = [
            'dictionaries' => [
                'dictionary1' => [
                    'search_fields' => [
                        'field1' => ['boost' => 1],
                        'field2' => ['boost' => 2],
                    ],
                    'typeahead_fields' => [
                        'title1.typeahead' => ['boost' => 2],
                    ],
                ],
                'dictionary2' => [
                    'search_fields' => [
                        'field2' => ['boost' => 1],
                        'field3' => ['boost' => 2],
                    ],
                    'typeahead_fields' => [
                        'title2.typeahead' => ['boost' => 2],
                    ]
                ],
            ]
        ];
    }

    public function testIndexAction()
    {
        $controller = new IndexController();
        $response = new Response();

        $controller->indexAction($response);

        $this->assertNotEmpty($response->getContent());
        $this->assertNotEmpty($response->getHeaders());
    }

    /**
     * @param $response
     * @return \Elastica\Client
     */
    protected function prepareElasticClientMock($response)
    {
        $response = ['hits' => ['hits' => array_map(function ($item) {
            return ['_source' => $item];
        }, $response)]];
        $client = $this->getMock('\Elastica\Client', ['request'], [], '', false);
        $client->expects($this->any())
            ->method('request')
            ->willReturn(new \Elastica\Response($response));

        return $client;
    }

    public function testSearchAction()
    {
        $controller = new IndexController();
        $controller->setElastic($this->prepareElasticClientMock([
            ['title' => 'value1', 'meta' => 'meta1 | meta2 | meta3', 'definition' => 'definition1'],
            ['title' => 'value2', 'meta' => 'meta4 | meta5 | meta6', 'definition' => 'definition1'],
        ]));
        $controller->setApp($this->app);


        $result = $controller->searchAction('навальніца');
        $expected = ['result' => [
            ['title' => 'value1', 'meta' => 'meta1, meta2, meta3', 'definition' => 'definition1'],
            ['title' => 'value2', 'meta' => 'meta4, meta5, meta6', 'definition' => 'definition1'],
        ]];

        $this->assertEquals($expected, $result);
    }

    public function testTypeaheadAction()
    {
        $controller = new IndexController();
        $controller->setElastic($this->prepareElasticClientMock([
            ['title' => 'value1', 'meta' => 'meta1 | meta2 | meta3', 'definition' => 'definition1'],
            ['title' => 'value2', 'meta' => 'meta4 | meta5 | meta6', 'definition' => 'definition1'],
        ]));
        $controller->setApp($this->app);

        $result = $controller->typeaheadAction('навальніца');
        $expected = ['result' => [
            ['title' => 'value1', 'meta' => 'meta1 | meta2 | meta3', 'definition' => 'definition1'],
            ['title' => 'value2', 'meta' => 'meta4 | meta5 | meta6', 'definition' => 'definition1'],
        ]];
        $this->assertEquals($expected, $result);
    }
}
