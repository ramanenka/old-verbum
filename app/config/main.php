<?php

$config = [
    'action_result_processor' => '\Verbum\Dict\ActionResultProcessor'
];

require 'routes.php';
require 'elastic.php';
require 'dictionaries.php';

return $config;
