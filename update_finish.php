<?php

require_once 'app/utils.php';

$params = [
    'source_current' => __DIR__,
    'source_latest' => sys_get_temp_dir() . '/script_latest',
    'source_backup' => sys_get_temp_dir() . '/script_backup',
    'dist' => realpath(__DIR__),
    'success' => true,
    'exclude_remove_dir' => [
        __DIR__ . '/cache',
        __DIR__ . '/var'
    ]
];

post_deploy_action($params);