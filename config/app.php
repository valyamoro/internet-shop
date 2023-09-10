<?php

$config = [
    'paths' => [
        'root' => __DIR__,
        'storage' => __DIR__ . '/storage_files',
        'uploads' => __DIR__ . '/uploads',
    ],
    'project' => [
        'debug' => false,
        'env' => getenv('.env', 'production'),

    ],

];

echo 'dqw';
print_r($config['project']);