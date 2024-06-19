<?php

namespace api\modules\auth\controllers;

use api\controllers\ApiBaseController;
use api\models\User;
use api\modules\auth\models\RegisterForm;
use common\modules\user\models\UserDevice;
use soft\helpers\Html;
use Yii;
use yii\base\Exception;

class RegisterController extends ApiBaseController
{
    /**
     * @return array
     * @throws Exception
     */
    public function actionPhone()
    {
        $model = new RegisterForm([
            'scenario' => 'phone',
        ]);

        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            if ($model->saveTempUser()) {
                return $this->success();
            }
        }
        return $this->error($model->firstErrorMessage);

    }

    /**
     * @return array
     */
    public function actionVerify()
    {
        $model = new RegisterForm([
            'scenario' => RegisterForm::SCENARIO_VERIFY,
        ]);

        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {

            return $this->success();
        }
        return $this->error($model->firstErrorMessage);
    }

    /**
     * @return array
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function actionSignUp()
    {
        $model = new RegisterForm([
            'scenario' => RegisterForm::SCENARIO_REGISTER,
        ]);

        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {

            $result = $model->register();

            if ($result != false) {

                /** @var User $user */
                $user = $result['user'];

                /** @var UserDevice $device */
                $device = $result['device'];


                $data = [
                    'id' => $user->id,
                    'username' => Html::encode($user->username),
                    'firstname' => Html::encode($user->firstname),
                    'lastname' => Html::encode($user->lastname),
                    'status' => $user->status,
                    'auth_key' => $device->token,
                    'statusName' => $user->statusName,
                    'imageUrl' => $user->getImageUrl(),
                    'allowed_devices_count' => $user->allowedActiveDevicesCount,
                    'activeTariff' => $user->lastActiveUserTariff ? $user->lastActiveUserTariff->tariff->name : 'FREE',
                    'activeTariffId' => $user->lastActiveUserTariff ? $user->lastActiveUserTariff->tariff->id : null,
                    'expiredAt' => $user->lastActiveUserTariff ? date('d.m.Y', $user->lastActiveUserTariff->expired_at) : '',
                ];

                return $this->success($data);
            }

        }
        return $this->error($model->firstErrorMessage);
    }
}
