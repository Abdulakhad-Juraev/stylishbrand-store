<?php

use common\targets\TelegramTarget;
use soft\db\Command;
use yii\db\Connection;
use yii\log\FileTarget;
use yii\queue\file\Queue;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
    'bootstrap' => ['gii', 'queue'],
    'language' => 'uz',
    'name' => 'Boshqaruv Panel',
    'components' => [
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error'],
                ],
                [
                    'class' => TelegramTarget::class,
                    'levels' => ['error'],
                    'except' => ['yii\web\HttpException:404', 'yii\web\HttpException:401'],
                ],
            ],
        ],
        'db' => [
            'class' => Connection::class,
            'enableLogging' => false,
            'commandClass' => Command::class,
            'attributes' => [
                PDO::ATTR_PERSISTENT => true
            ],
            'commandMap' => [
                'pgsql' => 'soft\db\Command', // PostgreSQL
                'mysqli' => 'soft\db\Command', // MySQL
                'mysql' => 'soft\db\Command', // MySQL
                'sqlite' => 'yii\db\sqlite\Command', // sqlite 3
                'sqlite2' => 'yii\db\sqlite\Command', // sqlite 2
                'sqlsrv' => 'soft\db\Command', // newer MSSQL driver on MS Windows hosts
                'oci' => 'yii\db\oci\Command', // Oracle driver
                'mssql' => 'soft\db\Command', // older MSSQL driver on MS Windows hosts
                'dblib' => 'soft\db\Command', // dblib drivers on GNU/Linux (and maybe other OSes) hosts
                'cubrid' => 'soft\db\Command', // CUBRID
            ]
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'i18n' => [
            'translations' => [
                'site*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@soft/i18n/messages',
                    'fileMap' => [
                        'site' => 'site.php',
                    ],
                ],
                'app' => [
                    'class' => 'yii\i18n\DbMessageSource',
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@common/runtime/cache'
        ],
        'view' => [
            'class' => 'soft\web\View',
        ],
        'formatter' => [
            'class' => 'soft\i18n\Formatter',
        ],
        'queue' => [
            'class' => Queue::class,
            'path' => '@common/runtime/queue',
            'dirMode' => 0777,
            'ttr' => 7200,
            'attempts' => 3
//            'db' => 'db', // DB connection component or its config
//            'tableName' => '{{%queue}}', // Table name
//            'channel' => 'default', // Queue channel key
//            'mutex' => \yii\mutex\MysqlMutex::class, // Mutex used to sync queries
        ],
    ],
//    'on beforeAction' => function ($event) {
//        if (Yii::$app instanceof \yii\web\Application) {
//            \common\components\LanguageHelper::setLanguage();
//        }
//    },
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],

        'gii' => [
            'class' => 'yii\gii\Module',
            'generators' => [
                'softModel' => [
                    'class' => 'soft\gii\generators\model\Generator',
                ],
                'softAjaxCrud' => [
                    'class' => 'soft\gii\generators\crud\Generator',
                ],
            ]
        ],
    ],

];
