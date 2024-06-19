<?php

namespace backend\modules\usermanager\controllers;

use backend\modules\usermanager\models\search\DoctorSearch;
use backend\modules\usermanager\models\search\UserSearch;
use backend\modules\usermanager\models\User;
use yii\web\Controller;
use Yii;

class UserController extends Controller
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

    //<editor-fold desc="CRUD" defaultstate="collapsed">

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $model = new User();
        $model->status = User::STATUS_ACTIVE;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            $model->auth_key = Yii::$app->security->generateRandomString();
            if ($model->save()) {
                $auth = \Yii::$app->authManager;
                $authorRole1 = $auth->getRole('admin');
                $authorRole = $auth->getRole('editor');
                $authorRole2 = $auth->getRole('director');
                if ($model->type_id == USer::TYPE_ID_ADMIN) {
                    $auth->assign($authorRole1, $model->getId());
                } elseif ($model->type_id == User::TYPE_ID_EDITOR) {
                    $auth->assign($authorRole, $model->getId());
                } elseif ($model->type_id == User::TYPE_ID_DIRECTOR) {
                    $auth->assign($authorRole2, $model->getId());
                }
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * @throws \yii\base\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->status = User::STATUS_ACTIVE;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if (!empty($model->password)) {
                $model->password_hash = Yii::$app->security->generatePasswordHash($model->password);
                $model->auth_key = Yii::$app->security->generateRandomString();
            }

            if ($model->save(false)) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    //</editor-fold>

    /**
     * @return User
     * @throws \yii\web\NotFoundHttpException
     */
    private function findModel($id)
    {
        $model = User::findOne($id);
        if ($model == null) {
            not_found();
        }

        return $model;
    }

}
