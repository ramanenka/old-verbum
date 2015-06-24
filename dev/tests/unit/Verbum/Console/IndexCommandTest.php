<?php


namespace Verbum\Console;

class IndexCommandTest extends \PHPUnit_Framework_TestCase
{
    protected $app;

    protected function setUp()
    {
        $this->app = $this->app = $this->getMockBuilder('Verbum\Core\App')
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->config = [
            'elastic' => [
                'index' => [
                    'settings' => [
                        'setting1' => 'value1',
                        'setting2' => 'value2',
                    ]
                ]
            ],
            'dictionaries' => [
                'dict1' => [
                    'mapping' => [
                        'mapping1' => 'mapping1',
                        'mapping2' => 'mapping2',
                    ]
                ]
            ]
        ];
    }

    public function testGeneralInfo()
    {
        $command = new IndexCommand();
        $this->assertNotEmpty($command->getName());
        $this->assertNotEmpty($command->getDescription());
    }

    /**
     * @param $response
     * @return \Elastica\Client
     */
    protected function prepareElasticClientMockForExecuteTest()
    {
        $client = $this->getMock('\Elastica\Client', ['request'], [], '', false);
        $client->expects($this->any())
            ->method('request')
            ->withConsecutive(
                [   // it should delete the index if it exists
                    $this->stringContains('verbum'),
                    $this->equalTo('DELETE'),
                ],
                [   // create the index with specified settings
                    $this->stringContains('verbum'),
                    $this->equalTo('PUT'),
                    $this->equalTo($this->app->config['elastic']['index']['settings']),
                ],
                [   // create the type with specified mapping
                    $this->stringContains('mapping'),
                    $this->equalTo('PUT'),
                    $this->equalTo([
                        'dict1' => [
                            'properties' => $this->app->config['dictionaries']['dict1']['mapping']
                        ]
                    ])
                ],
                [   // send first portion of documents to elastic
                    $this->stringContains('bulk'),
                    $this->equalTo('PUT'),
                    $this->callback(function($data) {
                        return strpos($data, 'Аазіс') > 0
                            && strpos($data, 'прыназоўнік') > 0
                            && strpos($data, 'зрабіць (-цца) якім-н') > 0
                            && strpos($data, 'slounik.jsp') > 0
                            && substr_count($data, '{"index":{"_index":"verbum","_type":"dict1"}}') == 4;
                    })
                ],
                [   // refresh index
                    $this->stringContains('refresh'),
                    $this->equalTo('POST')
                ],
                [   // send remaining documents to elastic
                    $this->stringContains('bulk'),
                    $this->equalTo('PUT'),
                    $this->callback(function($data) {
                        return strpos($data, 'Аб\'езд') > 0
                        && strpos($data, 'мужчынскі род') > 0
                        && strpos($data, 'Перад вёскай') > 0
                        && strpos($data, 'slounik.jsp') > 0
                        && substr_count($data, '{"index":{"_index":"verbum","_type":"dict1"}}') == 1;
                    })
                ],
                [   // refresh index
                    $this->stringContains('refresh'),
                    $this->equalTo('POST')
                ]
            )
            ->willReturnOnConsecutiveCalls(
                new \Elastica\Response(['acknowledged' => true]),
                new \Elastica\Response(['acknowledged' => true]),
                new \Elastica\Response(['acknowledged' => true]),
                new \Elastica\Response(['items' => [[],[],[],[]]]),
                new \Elastica\Response(['acknowledged' => true]),
                new \Elastica\Response(['items' => [[]]]),
                new \Elastica\Response(['acknowledged' => true])
            );

        return $client;
    }

    public function testExecute()
    {
        $command = new IndexCommand();
        $command->setApp($this->app);
        $command->setElastic($this->prepareElasticClientMockForExecuteTest());
        $command->setFileName('dev/tests/unit/Verbum/Console/dict1.xml');
        $command->setThreshold(3);

        $in = $this->getMock('\Symfony\Component\Console\Input\InputInterface', [], [], '', false);
        $out = $this->getMock('\Symfony\Component\Console\Output\OutputInterface', [], [], '', false);
        $command->run($in, $out);
    }
}
