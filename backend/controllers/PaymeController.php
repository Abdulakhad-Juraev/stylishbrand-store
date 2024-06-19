<?php


namespace backend\controllers;

use Yii;
use soft\web\SoftController;
use yii\web\Response;
use common\components\payme\Wallet;

/**
 * Payme controller
 */
class PaymeController extends SoftController
{

    public $enableCsrfValidation = false;

    public function init()
    {
        parent::init();
        Yii::$app->response->format = Response::FORMAT_JSON;

    }

    public function actionGetMe()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return (new Wallet(file_get_contents("php://input")))->response();
    }

}
