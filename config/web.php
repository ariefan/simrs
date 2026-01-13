<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'timeZone' => 'Asia/Ujung_Pandang',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'bootstrap' => ['log','AccessHistoryComp'],
    'components' => [
        // 'assetManager' => [
        //     'bundles' => [
        //         'yii\web\JqueryAsset' => [
        //             'jsOptions' => [ 'position' => \yii\web\View::POS_HEAD ],
        //         ],
        //     ],
        // ],
        
        'authClientCollection' => [
              'class' => 'yii\authclient\Collection',
              'clients' => [
                'facebook' => [
                  'class' => 'yii\authclient\clients\Facebook',
                  'authUrl' => 'https://www.facebook.com/dialog/oauth?display=popup',
                  'clientId' => '592787717574447',
                  'clientSecret' => '95be0688c79b10b8fd6578f35050f315',
                  // 'attributeNames' => ['name', 'email', 'first_name', 'last_name'],
                ],
              ],
            ],

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '55oRoAU3s8UOdRAgvBDmU1YC6Dam7nQZ',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
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
                'host' => 'srv11.niagahoster.com',
                'username' => 'admin@rekmed.com',
                'password' => 'Bismillah12345',
                'port' => '465',
                'encryption' => 'tls',
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
        'AccessHistoryComp' => [
            'class' => 'app\components\AccessHistoryComp'
        ],
        'db' => require(__DIR__ . '/db.php'),
        
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        
    ],
    'modules' => [
        'redactor' => [
            'class' => 'yii\redactor\RedactorModule',
            'uploadDir' => '@webroot/img/redactor',
            'uploadUrl' => '@web/img/redactor',
            'imageAllowExtensions'=>['jpg','png','gif']
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
            // enter optional module parameters below - only if you need to  
            // use your own export download action or custom translation 
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ],
        'datecontrol' => [
          'class' => '\kartik\datecontrol\Module',
          // see settings on http://demos.krajee.com/datecontrol#module
        ],
    ],
    'params' => $params,

];


return $config;
