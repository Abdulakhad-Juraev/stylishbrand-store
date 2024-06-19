<?php

namespace api\modules\profile\controllers;

use api\controllers\ApiBaseController;
use api\models\User;
use api\modules\profile\models\UpdatePasswordForm;
use api\modules\profile\models\UserPayment;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\UploadedFile;

class ProfileController extends ApiBaseController
{
    /**
     * @var string[]
     */
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];


    /**
     * @var bool
     */
    public $authRequired = true;

    /**
     * @return array
     */
    public function actionInfo()
    {
        return $this->success(Yii::$app->user->identity);
    }

    /**
     * @return array
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdateName()
    {
        $model = Yii::$app->user->identity;
        $model->scenario = User::SCENARIO_UPDATE_NAME;
        $old_img = $model->image;

        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {

            $image = UploadedFile::getInstanceByName('img');
            $url = Yii::getAlias('@frontend/web/uploads/user/' . $old_img);
            if (($image->size / (1024 * 1024) > 3) && $image) {
                return $this->error('The photo was larger than 3 MB. Please upload a smaller photo.');
            }

            if (in_array($image->extension, User::$extensions)) {
                if ($image) {
                    $image->saveAs('@frontend/web/uploads/user/' . 'user' . time() . '.' . $image->extension);
                    $model->image = 'user' . time() . '.' . $image->extension;
                    if (is_file($url)) {
                        unlink($url);
                    }
                } else {
                    $model->image = $old_img;
                }
                $model->update();

                return $this->success($model);
            }

            return $this->error("Qauydagi fayllarni yuklashga ruxsat berilgan: " . $model->getAllExtensionsName());
        }

        return $this->error($model->firstErrorMessage);


    }

    /**
     * @return array
     */
    public function actionUpdatePassword()
    {
        $model = new UpdatePasswordForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->changePassword()) {
            return $this->success();
        }
        return $this->error($model->firstErrorMessage);
    }


    /**
     * @return array
     */
    public function actionUserPayment()
    {
        $beginDate = Yii::$app->request->get('beginDate');

        $endDate = Yii::$app->request->get('endDate');

        $defaultPageSize = Yii::$app->request->get('defaultPageSize', 20);

        $query = UserPayment::find()
            ->andWhere(['user_id' => Yii::$app->user->id])
            ->orderBy(['created_at' => SORT_DESC]);

        if ($beginDate && $endDate) {

            $beginDate = strtotime($beginDate . ' 00:00:00');
            $endDate = strtotime($endDate . ' 23:59:59');

            $query->andWhere(['between', 'created_at', $beginDate, $endDate]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => $defaultPageSize,
            ]
        ]);

        return $this->success($dataProvider);
    }

    /**
     * @throws StaleObjectException
     */
    public function actionDelete()
    {
        $user = User::find()
            ->andWhere([
                'id' => user('id'),
                'status' => User::STATUS_ACTIVE
            ])->one();

        if ($user) {
            $user->delete();
            return $this->success();
        }

        return $this->error("Userni o'chirishda maummo yuz berdi!");
    }

}