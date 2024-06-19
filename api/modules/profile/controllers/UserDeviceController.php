<?php

namespace api\modules\profile\controllers;

use api\controllers\ApiBaseController;
use api\modules\profile\models\UserDevice;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;

class UserDeviceController extends ApiBaseController
{
    /**
     * @var string[]
     */
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public $authRequired = true;

    /**
     * User qurilmalari ro'yxati
     * @return array
     */
    public function actionIndex()
    {
        $query = UserDevice::find()
            ->andWhere(['user_id' => Yii::$app->user->id])
            ->active();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);


        return $this->success($dataProvider);
    }


    /**
     *
     * User o'ziga tegishli qurilmani o'chirish
     *
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete()
    {

        $deviceId = Yii::$app->request->get('device_id');

        if (!$deviceId) {
            return $this->error('Qurilma ID raqamini kiriting');
        }

        $user = Yii::$app->user->identity;

        $device = UserDevice::find()
            ->andWhere(['user_id' => $user->id])
            ->andWhere(['device_id' => $deviceId])
            ->one();


        if (!$device) {
            return $this->error('Qurilma topilmadi');
        }

        $device->delete();

        return $this->success();


    }

    /**
     *
     * User profildan chiqish
     *
     * @throws \yii\db\StaleObjectException
     */
    public function actionLogout()
    {

        $deviceId = Yii::$app->request->get('device_id');

        if (!$deviceId) {
            return $this->error('Qurilma ID raqamini kiriting');
        }

        $user = Yii::$app->user->identity;

        $device = UserDevice::find()
            ->andWhere(['user_id' => $user->id])
            ->andWhere(['device_id' => $deviceId])
            ->one();


        if (!$device) {
            return $this->error('Qurilma topilmadi');
        }

        $device->status = UserDevice::STATUS_INACTIVE;

        if ($device->save(false)) {
            Yii::$app->user->logout();
            return $this->success();
        }

        return $this->error();
    }
}
