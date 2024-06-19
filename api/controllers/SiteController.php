<?php

namespace api\controllers;

use yii\web\Controller;

class SiteController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->asJson([
            'status' => 200,
            'message' => 'success',
            'data' => [
                'author' => "Ulug'bek Muhammadjonov",
                'telegram' => 'https://t.me/U_Muhammadjonov',
                'email' => 'muhammadjonovulugbek98@gmail.com',
            ]
        ]);
    }

}