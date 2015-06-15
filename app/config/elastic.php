<?php

$config['elastic'] = [
    'connection' => [
        'host' => 'localhost',
        'port' => '9200',
    ],
    'index' => [
        'settings' => [
            'number_of_shards' => 1,
            'number_of_replicas' => 0,
            'analysis' => [
                'analyzer' => [
                    'titles_analyzer' => [
                        'tokenizer' => 'standard',
                        'filter' => ['standard', 'lowercase', 'title_typeahead_filter'],
                    ],
                ],
                'filter' => [
                    'title_typeahead_filter' => [
                        'type' => 'edgeNGram',
                        'min_gram' => 1,
                        'max_gram' => 256,
                    ],
                ],
            ],
        ],
    ],
];
