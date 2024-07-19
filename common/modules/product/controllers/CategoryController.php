<?php

namespace common\modules\product\controllers;

use common\modules\product\models\search\CategoryCharacterSearch;
use Yii;
use yii\web\Response;
use soft\web\SoftController;
use yii\web\NotFoundHttpException;
use common\modules\product\models\Category;
use common\modules\product\models\search\CategorySearch;
use common\modules\product\models\search\SubCategorySearch;

class CategoryController extends SoftController
{
    /**
     * @return mixed
     */
    public function actionIndex(): mixed
    {
        $searchModel = new CategorySearch();
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
        $model = new Category([
            'status' => Category::STATUS_ACTIVE
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
     * @return Category
     * @throws yii\web\NotFoundHttpException
     */
    public function findModel($id)
    {
        $model = Category::find()->andWhere(['id' => $id])->one();
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
        $model->status = ($model->status == Category::STATUS_ACTIVE)
            ? Category::STATUS_INACTIVE
            : Category::STATUS_ACTIVE;

        // Save without validation to avoid extra database queries
        if (!$model->save(false)) {
            throw new \RuntimeException('Failed to save the model');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionSubCategory($id): string
    {
        $model = $this->findModel($id);

        $searchModel = new SubCategorySearch();

        $dataProvider = $searchModel->search($model->getSubCategories());

        return $this->render('sub-category', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionCharacter($id): string
    {
        $model = $this->findModel($id);
        $searchModel = new CategoryCharacterSearch();
        $dataProvider = $searchModel->search($model->getCategoryCharacters());

        return $this->render('character', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
}
