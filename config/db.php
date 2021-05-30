<?php
use codemix\yii2confload\Config;

$config = new Config(__DIR__.'/../');

return [
    'class' => 'yii\db\Connection',
    'dsn' => $config->env('DB_CONNECTION', 'mysql:host=localhost;dbname=airagri'),
    'username' => $config->env('DB_USERNAME', 'root'),
    'password' => $config->env('DB_PASSWORD', ''),
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
