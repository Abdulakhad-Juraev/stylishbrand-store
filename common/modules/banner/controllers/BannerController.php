<?php

namespace common\modules\banner\controllers;

use Yii;
use common\modules\banner\models\Banner;
use common\modules\banner\models\search\BannerSearch;
use soft\web\SoftController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class BannerController extends SoftController
{
    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BannerSearch();
        $dataProvider = $searchModel->search();

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
        $model = new Banner([
            'status' => Banner::STATUS_ACTIVE
        ]);
        return $this->ajaxCrud($model)->createAction();
    }

    /**
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (!$model->getIsUpdatable()) {
            forbidden();
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
        if (!$model->getIsDeletable()) {
            forbidden();
        }
        $model->delete();
        return $this->ajaxCrud($model)->deleteAction();
    }

    /**
     * @param $id
     * @return Banner
     * @throws yii\web\NotFoundHttpException
     */
    public function findModel($id)
    {
        $model = Banner::find()->andWhere(['id' => $id])->one();
        if ($model == null) {
            not_found();
        }
        return $model;
    }
}
