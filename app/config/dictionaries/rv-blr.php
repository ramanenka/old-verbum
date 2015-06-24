<?php

$config['dictionaries']['rv-blr'] = [
    'params' => [],
    'mapping' => [
        'title' => [
            'type' => 'string',
            'fields' => [
                'typeahead' => [
                    'type' => 'string',
                    'index_analyzer' => 'titles_analyzer',
                    'search_analyzer' => 'standard',
                ]
            ],
        ],
        'meta' => ['type' => 'string'],
        'definition' => ['type' => 'string'],
        'source' => ['type' => 'string'],
    ],
    'search_fields' => [
        'title' => ['boost' => 2],
        'definition' => ['boost' => 1],
    ],
    'typeahead_fields' => [
        'title.typeahead' => ['boost' => 2],
    ],
];
