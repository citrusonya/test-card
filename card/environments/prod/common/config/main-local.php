<?php

return [
    'components' => [
        'db'     => [
            'class'               => 'yii\db\Connection',
            'dsn'                 => 'pgsql:host=pgsql;port=5432;dbname=card',
            'username'            => 'card',
            'password'            => 'cardLocal',
            'charset'             => 'utf8',
            'enableSchemaCache'   => true,
            'schemaCacheDuration' => 3600,
            'schemaMap'           => [
                'pgsql' => [
                    'class'         => 'yii\db\pgsql\Schema',
                    'defaultSchema' => 'main'
                ]
            ],
        ],
        'mailer' => [
            'class'    => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
