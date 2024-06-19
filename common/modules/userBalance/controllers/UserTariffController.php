<?php

namespace common\modules\userBalance\controllers;

use common\modules\order\models\Order;
use common\modules\user\models\UserPayment;
use common\modules\userBalance\models\search\UserTariffSearch;
use common\modules\userBalance\models\UserTariff;
use soft\web\SoftController;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class UserTariffController extends SoftController
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'bulk-delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserTariffSearch();
        $query = UserTariff::find()
            ->orderBy(['created_at' => SORT_DESC]);
        $dataProvider = $searchModel->search($query);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->ajaxCrud($model)->viewAction();
    }

    /**
     * @return array|string
     */
    public function actionCreate($id = null)
    {
        $model = new UserTariff([
            'user_id' => $id
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save() && Yii::$app->request->isAjax) {
            $lastTariff = $model->user->lastUserTariff;
            if ($lastTariff) {
                $begin = $lastTariff->expired_at;
                if ($begin < today()) {
                    $begin = today();
                }
            } else {
                $begin = today();
            }
            $model->price = $model->tariff->price;
            $model->started_at = $begin;
            $model->expired_at = strtotime("+" . $model->tariff->getDuration(), $begin);
            $model->save();

            $model->user->addBalance($model->price, $model->payment_type_id, Order::$type_id_tariff, null, $model->tariff_id, $model->id);
            return $this->ajaxCrud($model)->closeModalResponse();
        }

        return $this->ajaxCrud($model)->createAction();
    }

    /**
     * @param integer $id
     * @return array|string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $lastUserTariff = $model->user->lastUserTariff;

        if ($model->id < $lastUserTariff->id) {

            forbidden("Ushbu obundan keyin yana obuna sotib olingan");

        }
        if (!$model->getIsUpdatable()) {
            forbidden();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save() && Yii::$app->request->isAjax) {

            $payment = UserPayment::findOne(['table_id' => $model->id]);

            if ($payment) {
                $payment->delete();
            }
            $model->user->addBalance($model->price, $model->payment_type_id, Order::$type_id_tariff, null, $model->tariff_id, $model->id);
            return $this->ajaxCrud($model)->closeModalResponse();
        }
        return $this->ajaxCrud($model)->updateAction();
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $lastUserTariff = $model->user->lastUserTariff;
        if ($model->id < $lastUserTariff->id) {
            forbidden("Ushbu obundan keyin yana obuna sotib olingan");
        }

        if (!$model->getIsDeletable()) {
            forbidden();
        }
        $model->delete();

        $payment = UserPayment::findOne(['table_id' => $model->id]);

        if ($payment) {
            $payment->delete();
        }

        return $this->ajaxCrud($model)->deleteAction();
    }

    /**
     * @param $id
     * @return UserTariff
     * @throws yii\web\NotFoundHttpException
     */
    public function findModel($id)
    {
        $model = UserTariff::find()->andWhere(['id' => $id])->one();
        if ($model == null) {
            not_found();
        }
        return $model;
    }
}
