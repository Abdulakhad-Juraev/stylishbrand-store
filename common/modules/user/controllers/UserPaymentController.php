<?php

namespace common\modules\user\controllers;

use Yii;
use common\modules\user\models\UserPayment;
use common\modules\user\models\search\UserPaymentSearch;
use soft\web\SoftController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class UserPaymentController extends SoftController
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
        $searchModel = new UserPaymentSearch();
        $query = UserPayment::find()
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
     * @return string
     */
    public function actionCreate()
    {
        $model = new UserPayment();
        return $this->ajaxCrud($model)->createAction();
    }

//    /**
//    * @param integer $id
//    * @return string
//    * @throws NotFoundHttpException if the model cannot be found
//    */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//        if (!$model->getIsUpdatable()) {
//            forbidden();
//        }
//        return $this->ajaxCrud($model)->updateAction();
//    }

//    /**
//    * @param integer $id
//    * @return mixed
//    * @throws NotFoundHttpException if the model cannot be found
//    */
//    public function actionDelete($id)
//    {
//        $model = $this->findModel($id);
//        if (!$model->getIsDeletable()) {
//            forbidden();
//        }
//        $model->delete();
//        return $this->ajaxCrud($model)->deleteAction();
//    }

    /**
     * @param $id
     * @return UserPayment
     * @throws yii\web\NotFoundHttpException
     */
    public function findModel($id)
    {
        $model = UserPayment::find()->andWhere(['id' => $id])->one();
        if ($model == null) {
            not_found();
        }
        return $model;
    }
}
