<?php

namespace backend\controllers;

class ClickController extends \backend\modules\click\controllers\ClickController
{

    public $writeLogs = true;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if ($action->id == "prepare" || $action->id == "complete") {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

}
