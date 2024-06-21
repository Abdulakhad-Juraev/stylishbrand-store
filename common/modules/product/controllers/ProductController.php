<?php

namespace common\modules\product\controllers;

use common\modules\galleryManager\GalleryManagerAction;
use DateTime;
use Yii;
use common\modules\product\models\Product;
use common\modules\product\models\search\ProductSearch;
use soft\web\SoftController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ProductController extends SoftController
{

    /**
     * Lists all Product models.
     *
     * @return array[]|string
     */
    public function actions()
    {
        return [
            'galleryApi' => [
                'class' => GalleryManagerAction::className(),
                // mappings between type names and model classes (should be the same as in behaviour)
                'types' => [
                    'product' => Product::className()
                ]
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
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
        date_default_timezone_set('Asia/Tashkent');
        $model = new Product([
            'status' => Product::STATUS_ACTIVE,
            'published_at' => date('Y-m-d H:i'),
            'expired_at' => (new DateTime(date('Y-m-d H:i')))->modify('+3 month')->format('Y-m-d H:i')
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
     * @return Product
     * @throws yii\web\NotFoundHttpException
     */
    public function findModel($id)
    {
        $model = Product::find()->andWhere(['id' => $id])->one();
        if ($model == null) {
            not_found();
        }
        return $model;
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionImage($id): string
    {
        $model = $this->findModel($id);
        return $this->render('image', [
            'model' => $model,
        ]);
    }

}
