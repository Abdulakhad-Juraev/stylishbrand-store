<?php
/*
* @author Muhammadjonov Ulug'bek <muhammadjonovulugbek98@gmail.com>
* @link telegram: https://t.me/U_Muhammadjonov
* @date 23.10.2022, 22:12
*/

namespace api\modules\profile\controllers;

use api\controllers\ApiBaseController;
use api\modules\profile\models\UpdateUsername;
use Yii;

class UpdateUsernameController extends ApiBaseController
{
    public $authRequired = true;

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionPhone(): array
    {

        $model = new UpdateUsername([
            'scenario' => UpdateUsername::SCENARIO_PHONE,
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
     * @throws \yii\base\Exception
     */
    public function actionVerify(): array
    {

        $model = new UpdateUsername([
            'scenario' => UpdateUsername::SCENARIO_VERIFY,
        ]);

        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            $model->updatePhone();
            return $this->success();
        }
        return $this->error($model->firstErrorMessage);

    }
}