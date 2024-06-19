<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@soft', dirname(dirname(__DIR__)) . '/soft');

Yii::$container->set('yii\data\Pagination', [
    'pageSizeLimit' => [1, 1000],
]);
Yii::$container->set('yii\filters\Cors', 'common\filter\CorsFilter');

