<?php

namespace soft\actions\site;

use Yii;
use yii\base\Action;

class LogoutAction extends Action
{

    public $returnUrl;

    public function run()
    {
        Yii::$app->user->logout();

        if ($this->returnUrl){
            return $this->controller->redirect($this->returnUrl);
        }

        return $this->controller->goHome();
    }

}
