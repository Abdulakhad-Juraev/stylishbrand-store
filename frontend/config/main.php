<?php
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
    'homeUrl' => ['branch/'],
    'components' => [
        'request' => [
            'baseUrl' => '/',
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
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
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'class' => 'soft\web\UrlManager',
            'baseUrl' => '/',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<lang:\w+>/site/index' => 'site/index',
                '<lang:\w+>/' => 'site/index',
                '<lang:\w+>/site/contact' => 'site/contact',
                '<lang:\w+>/video/index/<id>' => 'video/index',
                '<lang:\w+>/leadership/index/<id>' => 'leadership/index',
                '<lang:\w+>/news/all-news/<slug>' => 'news/all-news',
                '<lang:\w+>/news/detail/<slug>' => 'news/detail',
                '<lang:\w+>/page/index/<slug>' => 'page/index',
            ],
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'baseUrl' => '/template',
                    'js' => [
                        'voise/js/jquery.min.js',
                        'js/jquery.fancybox.min.js',
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => null,
                    'baseUrl' => '/template',
                    'css' => [
                        'fontawesome-free-6.2.0-web/css/all.css',
                        'css/sign/jquery.fancybox.min.css',
                        'css/sign/bootstrap.min.css',
                        'css/sign/animate.min.css',
                        'css/sign/swiper-bundle.min.css',
                    ]
                ],

                'yii\bootstrap\BootstrapPluginAsset' => [
                    'sourcePath' => null,
                    'baseUrl' => '/template',
                    'js' => [
                        'voise/js/bootstrap.bundle.js',
                        'voise/js/libs.js',
                        'js/bootstrap.bundle.min.js',
                    ]
                ],
                'yii\bootstrap4\BootstrapAsset' => [
                    'sourcePath' => null,
                    'css' => [],
                    'depends' => [
                        'yii\bootstrap\BootstrapAsset'
                    ],
                ],
                'yii\bootstrap4\BootstrapPluginAsset' => [
                    'sourcePath' => null,
                    'js' => [],
                    'depends' => [
                        'yii\bootstrap\BootstrapPluginAsset'
                    ]
                ],

            ]
        ],
    ],
    'modules' => [
        'pdfjs' => [
            'class' => '\yii2assets\pdfjs\Module',
        ],

    ],
//    'on beforeAction' => function ($event) {
//        $session = Yii::$app->session;
//        $value = $session->get('telegram');
//        if (!$value) {
//            $session->set('telegram', "Telegram");
//        }
//    },

    'params' => $params,
];
