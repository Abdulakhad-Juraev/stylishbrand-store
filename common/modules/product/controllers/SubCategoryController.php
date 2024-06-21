<?php

namespace common\modules\product\controllers;

use Yii;
use common\modules\product\models\SubCategory;
use common\modules\product\models\search\SubCategorySearch;
use soft\web\SoftController;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SubCategoryController extends SoftController
{

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubCategorySearch();
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
        $model = new SubCategory();
        return $this->ajaxCrud($model)->createAction();
    }

    public function actionCreate2($category_id)
    {

        $model = new SubCategory([
            'status' => SubCategory::STATUS_ACTIVE
        ]);

        if ($model->load(Yii::$app->request->post())) {
            $model->category_id = $category_id;
            $model->save();
        }

        return $this->ajaxCrud($model, [
            'view' =>'create2'
        ])->createAction();
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
     * @return SubCategory
     * @throws yii\web\NotFoundHttpException
     */
    public function findModel($id)
    {
        $model = SubCategory::find()->andWhere(['id' => $id])->one();
        if ($model == null) {
            not_found();
        }
        return $model;
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionChange($id)
    {
        $model = $this->findModel($id);

        // Toggle status
        $model->status = ($model->status == SubCategory::STATUS_ACTIVE)
            ? SubCategory::STATUS_INACTIVE
            : SubCategory::STATUS_ACTIVE;

        // Save without validation to avoid extra database queries
        if (!$model->save(false)) {
            throw new \RuntimeException('Failed to save the model');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}
