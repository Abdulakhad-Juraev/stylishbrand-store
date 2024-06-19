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
                'auth_key' => $model->device->token ?? '',
                'allowed_devices_count' => $user->allowedActiveDevicesCount,
                'activeTariff' => $model->user->lastActiveUserTariff ? $model->user->lastActiveUserTariff->tariff->name : 'FREE',
                'activeTariffId' => $model->user->lastActiveUserTariff ? $model->user->lastActiveUserTariff->tariff->id : null,
                'expiredAt' => $model->user->lastActiveUserTariff ? date('d.m.Y', $model->user->lastActiveUserTariff->expired_at) : '',
            ]);
        }

        return $this->error($model->firstErrorMessage);

    }
}
