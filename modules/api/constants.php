<?php
use codemix\yii2confload\Config;
$config = new Config(__DIR__.'/../../');

return [
    'reponse_code' => [
        'request_success' => '0000',
        'request_invalid' => '0001',
        'request_failed'  => '0002',
    ],
    'constants' => [
        'base_url' => $config->env('FE_BASE_URL', '')
    ]
];
