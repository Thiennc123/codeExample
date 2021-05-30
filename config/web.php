<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
use codemix\yii2confload\Config;
Config::initEnv('../');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '_8ZufF3p9DndSzSrqBEm6tBAPDhjBhTr',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => yii\redis\Cache::class,
        ],
        'session' => [
            'class' => yii\redis\Session::class,
        ],
        'mutex' => [
            'class' => yii\redis\Mutex::class,
        ],
        'user' => [
            'identityClass' => 'app\modules\api\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => Config::env('MAIL_HOST', 'smtp.gmail.com'), // e.g. smtp.mandrillapp.com or smtp.gmail.com
                'username' => Config::env('MAIL_USERNAME', ''),
                'password' => Config::env('MAIL_PASSWORD', ''),
                'port' =>  Config::env('MAIL_PORT', '587'), // Port 25 is a very common port too
                'encryption' =>  Config::env('MAIL_ENCRYPTION', 'tls'), // It is often used, check your provider or mail server specs
             ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'redis' => [
            'class' => \yii\redis\Connection::class,
            'hostname' => Config::env('REDIS_HOST', '127.0.0.1'),
            'port' => Config::env('REDIS_PORT', '6379'),
            'database' => 0,
            'retries' => 1,
        ],
        'queue' => [
            'class' => \yii\queue\redis\Queue::class,
            'redis' => 'redis',
            'channel' => 'queue',
        ],
        'urlManager' => [
            'class'=>'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/web/user',
                    'extraPatterns' => [
                        'GET check-email-exists' => 'check-email-exists',
                        'OPTIONS check-email-exists-in-property' => 'options',
                        'GET check-email-exists-in-property' => 'check-email-exists-in-property',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/web/profile',
                    'extraPatterns' => [
                        'OPTIONS <id:\w+>/check-current-password' => 'options',
                        'POST <id:\w+>/check-current-password' => 'check-current-password',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/web/session',
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/web/registration',
                    'extraPatterns' => [
                        'OPTIONS resend-email-confirmation' => 'options',
                        'GET resend-email-confirmation' => 'resend-email-confirmation',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/web/verification',
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/web/property',
                    'extraPatterns' => [
                        'OPTIONS <id:\w+>/invite' => 'options',
                        'POST <id:\w+>/invite' => 'invite',
                        'OPTIONS <id:\w+>/cancel-invite' => 'options',
                        'GET <id:\w+>/cancel-invite' => 'cancel-invite',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/web/permission',
                    'extraPatterns' => [
                        'OPTIONS get-user-permission' => 'options',
                        'GET get-user-permission' => 'get-user-permission',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/web/primary-object',
                    'extraPatterns' => [
                        'OPTIONS <id:\w+>/update-area' => 'options',
                        'PUT <id:\w+>/update-area' => 'update-area',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/web/task',
                    'extraPatterns' => [
                        'OPTIONS get-board' => 'options',
                        'GET get-board' => 'get-board',
                        'OPTIONS <id:\w+>/change-status' => 'options',
                        'PUT <id:\w+>/change-status' => 'change-status',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/web/secondary-object',
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/web/asset',
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/web/mob',
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/web/breed',
                ],
            ],
        ],
        'jwt' => [
            'class' => \sizeg\jwt\Jwt::class,
            'key'   => 'secret',
            'jwtValidationData' => \app\modules\api\components\JwtValidationData::class,
        ],
    ],
    'modules' => [
        'api' => [
            // 'basePath' => '@app/modules/api',
            'class' => 'app\modules\api\Module',
            // set custom module properties here ...
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
