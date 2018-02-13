<?php

$params = require(__DIR__ . '/params.php');
date_default_timezone_set('Europe/Moscow');

$config = [
    'id' => 'camp',
    'name' => 'Camp-Centr',
    'language'=>'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'office' => [
            'class' => 'app\modules\office\Module',
        ],
        'partner' => [
            'class' => 'app\modules\partner\Module',
        ],
        'manage' => [
            'class' => 'app\modules\manage\Module',
        ],
    ],
    'components' => [
        'assetManager' => ['forceCopy' => true],

        'formatter' => [
            'thousandSeparator' => '',
        ],

        'request' => [
            'cookieValidationKey' => 'Axr6KwDu6kuVJKK3lzWKcrw6MWF6J7Dx',
            'enableCsrfCookie' => true,
            'enableCsrfValidation' => true,
            'enableCookieValidation' => true,
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'suffix' => '.html',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,// true
            'rules' => [
                '' => 'site/index',

                'payment/<action:[\w\-]+>' => 'payment/<action>',
                
                // http://camp-centr.ru/russia/crimea/camp/dolp-im-av-kazakevicha-30.html
                '<country:[\w\-]+>/<region:[\w\-]+>/camp/<alias:[\w\-]+>' => 'site/camp',
                
                // http://camp-centr.ru/camps/country/russia.html
                'camps/<type:[\w\-]+>/<alias:[\w\-]+>' => 'site/camps',
                
                // http://camp-centr.ru/camps/russia--krym--ozdorovitelnie.html
                'camps/<alias:[\w\-]+>' => 'site/camps',

                'new/<alias:[\w\-]+>' => 'site/new',
                
                '<action:(camps|camp-map|camp-points)>' => 'site/<action>',
                
                '<controller:(ajax|auth)>/<action:[\w\-]+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:(ajax|auth)>/<action:[\w\-]+>'=>'<controller>/<action>',

                '<module:(manage|office|partner)>/<controller:[\w\-]+>/<action:[\w\-]+>/<id:\d+>'=>'<module>/<controller>/<action>',
                '<module:(manage|office|partner)>/<controller:[\w\-]+>/<action:[\w\-]+>'=>'<module>/<controller>/<action>',

                '<alias:[\w\-\/]+>' => 'site/page',
                
                '<controller:[\w\-]+>/<action:[\w\-]+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:[\w\-]+>/<action:[\w\-]+>'=>'<controller>/<action>',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Users',
            'loginUrl' => 'site/index',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error','warning'],
                ],
                [
                    'class' => 'yii\log\EmailTarget',
                    'levels' => ['error', 'warning'],
                    'except' => [
                        'yii\web\HttpException:403',
                        'yii\web\HttpException:404',
                        'yii\debug\*',
                    ],
                    'message' => [
                        'from' => ['error@camp-centr.ru' => 'CampCentr'],
                        'to' => [$params['adminEmail']],
                        'subject' => 'Site error',
                    ],
                    'logVars' => ['_SERVER', '_POST'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

\Yii::$container->set('yii\grid\GridView', [
    'layout' => '<div class="grid-summary">{summary}</div>{items}<div class="grid-pagination">{pager}</div>',
    'summary' => 'Записи <strong>{begin}</strong>-<strong>{end}</strong> из <strong>{totalCount}</strong>',
    'pager' => [
        'firstPageLabel' => 'Первая',
        'nextPageLabel' => '&rarr;',
        'prevPageLabel' => '&larr;',
        'lastPageLabel' => 'Последняя'
    ],
    'emptyText' => 'Записи не найдены',
    'emptyTextOptions' => ['class' => 'text-center'],
    'tableOptions' => ['class' => 'table table-hover table-bordered'],
    'filterRowOptions' => ['class' => 'form-group-sm row-filters'],
]);

\Yii::$container->set('yii\widgets\ActiveForm', [
    'enableClientValidation' => true,
    'enableClientScript' => true,
    'enableAjaxValidation' => false,
    'options' => [
        'class' => 'form-horizontal',
    ],
    'fieldConfig' => [
        'template' => '<div class="col-xs-12 col-lg-4 text-right">{label}</div><div class="col-xs-12 col-lg-7">{input}{error}</div>',
        'labelOptions' => [
            'class' => 'control-label'
        ]
    ],
]);

\Yii::$container->set('yii\validators\RequiredValidator', [
    'message' => 'Введите «{attribute}»',
]);

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = ['class' => 'yii\gii\Module',
                                 'allowedIPs' => ['::1','127.0.0.1','94.19.219.69','78.140.198.50']];
}

return $config;