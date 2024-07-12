<?php

namespace common\modules\product\controllers;

use Yii;
use common\modules\product\models\CategoryCharacter;
use common\modules\product\models\search\CategoryCharacterSearch;
use soft\web\SoftController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class CategoryCharacterController extends SoftController
{
    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategoryCharacterSearch();
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
    public function actionCreate($category_id)
    {
        $model = new CategoryCharacter([
            'category_id' => $category_id,
            'status' => CategoryCharacter::STATUS_ACTIVE
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
     * @return CategoryCharacter
     * @throws yii\web\NotFoundHttpException
     */
    public function findModel($id)
    {
        $model = CategoryCharacter::find()->andWhere(['id' => $id])->one();
        if ($model == null) {
            not_found();
        }
        return $model;
    }
}
