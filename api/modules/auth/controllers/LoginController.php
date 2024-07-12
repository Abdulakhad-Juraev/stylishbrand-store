<?php

namespace api\modules\auth\controllers;

use api\controllers\ApiBaseController;
use api\models\User;
use api\modules\auth\models\LoginForm;
use soft\helpers\Html;

class LoginController extends ApiBaseController
{
    /**
     * @return array
     */
    public function actionLogin()
    {
        $model = new LoginForm();

        if ($model->load($this->post(), '') && $model->validate()) {

            $user = $model->user;

            return $this->success([
                'id' => $user->id,
                'username' => Html::encode($user->username),
                'firstname' => Html::encode($user->firstname),
                'lastname' => Html::encode($user->lastname),
                'imageUrl' => $user->getImageUrl(),
                'status' => $user->status,
                'statusName' => $user->statusName,
                'auth_key' => $user->auth_key,
            ]);
        }

        return $this->error($model->firstErrorMessage);

    }
}
