<?php

namespace api\modules\auth\controllers;

use api\controllers\ApiBaseController;
use api\modules\auth\models\ResetForm;
use common\models\User;
use common\modules\user\models\UserDevice;
use soft\helpers\Html;
use Yii;
use yii\db\Exception;

class ResetController extends ApiBaseController
{

    /**
     * @return array
     * @throws Exception
     */
    public function actionPhone(): array
    {

        $model = new ResetForm([
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
    public function actionVerify(): array
    {

        $model = new ResetForm([
            'scenario' => ResetForm::SCENARIO_VERIFY,
        ]);

        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            return $this->success();
        }
        return $this->error($model->firstErrorMessage);

    }

    /**
     * @throws \yii\base\Exception
     * @throws \Exception
     */
    public function actionResetPassword()
    {

        $model = new ResetForm([
            'scenario' => ResetForm::SCENARIO_RESET_PASSWORD,
        ]);

        if ($model->load(Yii::$app->request->post(), '')) {

            $result = $model->resetPassword();

            if (!$result) {
                return $this->error($model->firstErrorMessage);
            }

            /** @var User $user */
            $user = $result['user'];
            /** @var UserDevice $device */
            $device = $result['device'];

            return $this->success([
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
            ]);

        }
        return $this->error($model->firstErrorMessage);
    }
}
