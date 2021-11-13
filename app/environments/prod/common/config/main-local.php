<?php

return [
    'components' => [
        'db'     => [
            'class'               => 'yii\db\Connection',
            'dsn'                 => 'pgsql:host=localhost;port=5432;dbname=postgres',
            'username'            => 'postgres',
            'password'            => '123456',
            'charset'             => 'utf8',
            'enableSchemaCache'   => true,
            'schemaCacheDuration' => 3600,
            'schemaMap'           => [
                'pgsql' => [
                    'class'         => 'common\components\pgsql\Schema',
                    'defaultSchema' => 'public'
                ]
            ],
        ],
        'mailer' => [
            'class'    => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
