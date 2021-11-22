<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id'                  => 'app-frontend',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components'          => [
        'request'      => [
            'csrfParam'           => '_csrf-frontend',
            'cookieValidationKey' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0ZXN0IjoiY2FyZC5jb20ifQ.4k85dFfFgL9HKunUuyqT-FH6mVl2rsQDs_yXrf8mk2w',
            'parsers'             => [
                'application/json' => 'yii\web\JsonParser'
            ],
        ],
        'user'         => [
            'identityClass'   => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie'  => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session'      => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'   => 'yii\log\FileTarget',
                    'logVars' => [],
                    'levels'  => ['warning'],
                ],
                [
                    'class'      => 'yii\log\FileTarget',
                    'logVars'    => [],
                    'levels'     => ['info'],
                    'categories' => ['success'],
                ],
                [
                    'class'      => 'yii\log\FileTarget',
                    'logVars'    => [],
                    'levels'     => ['error'],
                    'categories' => ['error'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager'   => [
            'enablePrettyUrl'     => true,
            'showScriptName'      => false,
            'enableStrictParsing' => true,
            'rules'               => [
                '/' => 'site/card',
                ['class' => 'yii\rest\UrlRule', 'controller' => ['token' => 'token/token']],
            ],
        ],
    ],
    'params'              => $params,
    'controllerMap'       => [
        'token' => 'app\controllers\TokenController',
    ],
];
