<?php

$events = [
    'kernel_request' => [
        'route_kernel_request' => [
            'di_alias' => 'route_observer',
            'method'   => 'kernelRequest',
        ],
    ],
];

return $events;
