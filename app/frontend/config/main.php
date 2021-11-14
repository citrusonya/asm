<?php

use common\models\User;
use yii\log\FileTarget;
use yii\rest\UrlRule;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'api' => [
            'class' => 'frontend\modules\api\Module'
        ]
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'cookieValidationKey' => 'd49ipDXNBI0afLqoza9dkfYng6bPycKB',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser'
            ],
        ],
        'user' => [
            'identityClass' => User::class,
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl'     => true,
            'showScriptName'      => false,
            'enableStrictParsing' => true,
            'rules'               => [
                '/'                    => 'site/login',
                'login'                => 'site/login',
                'signup'               => 'site/signup',
                'logout'               => 'site/logout',
                'rules'                => 'rule/index',
                'rule/create'          => 'rule/create',
                'rule/edit/<id:\d+>'   => 'rule/edit',
                'rule/delete/<id:\d+>' => 'rule/delete',
                'rule/view/<id:\d+>'   => 'rule/view',
                'users'                => 'user/index',
                'user/create'          => 'user/create',
                'user/edit/<id:\d+>'   => 'user/edit',
                'user/delete/<id:\d+>' => 'user/delete',
                'user/view/<id:\d+>'   => 'user/view',
                ['class' => 'yii\rest\UrlRule', 'controller' => ['api/role' => 'role-api']],
                ['class' => 'yii\rest\UrlRule', 'controller' => ['api/user' => 'user-api']],
            ],
        ],
    ],
    'params' => $params,
];
