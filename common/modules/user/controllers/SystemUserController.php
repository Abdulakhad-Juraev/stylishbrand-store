<?php

namespace common\modules\user\controllers;

use common\modules\user\models\search\UserDeviceSearch;
use common\modules\user\models\search\UserSearch;
use common\modules\user\models\User;
use common\modules\userbalance\actions\CreateUserPaymentAction;
use common\modules\userbalance\models\search\UserPaymentSearch;
use common\modules\userbalance\models\search\UserTariffSearch;
use soft\web\SoftController;
use Yii;
use yii\data\ActiveDataProvider;

class SystemUserController extends SoftController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'fill-balance' => [
                'class' => CreateUserPaymentAction::class,
            ]
        ];
    }

    //<editor-fold desc="CRUD" defaultstate="collapsed">

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $query = User::find()->andWhere(['type_id' => User::TYPE_IS_SYSTEM_USER]);
        $dataProvider = $searchModel->search($query);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @throws \yii\base\Exception
     * @throws \Exception
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = User::SCENARIO_REGISTER;
        $model->status = User::STATUS_ACTIVE;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->type_id = User::TYPE_IS_SYSTEM_USER;
            $model->notice_status = User::NOTICE_STATUS_INACTIVE;
            $model->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            $model->auth_key = Yii::$app->security->generateRandomString();
            if ($model->save(false)) {
                $roleNames = Yii::$app->request->post('RoleName', []);
                $model->assignNewRoles($roleNames);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * @throws \yii\base\Exception
     * @throws \yii\web\NotFoundHttpException
     * @throws \Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->can('admin')) {
            forbidden();
        }
        $model->status = User::STATUS_ACTIVE;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if (!empty($model->password)) {
                $model->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            }

            if ($model->save(false)) {
                $roleNames = Yii::$app->request->post('RoleName', []);
                $model->updateRoles($roleNames);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * @param $id
     * @return array|string
     * @throws \Yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     * @throws \Exception
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->ajaxCrud($model)->viewAction();
    }

    /**
     * @param $id
     * @return array
     * @throws \Throwable
     * @throws \Yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->can('admin') || $model->can('system-user')) {
            forbidden();
        }
        return $this->ajaxCrud($model)->deleteAction();
    }

    /**
     * @return User
     * @throws \yii\web\NotFoundHttpException
     */
    private function findModel($id)
    {
        /** @var User $model */
        $model = User::findOne(['id' => $id]);
        if ($model == null) {
            not_found();
        }
        return $model;
    }

    //</editor-fold>

}