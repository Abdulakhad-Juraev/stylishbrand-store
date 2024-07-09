<?php

namespace common\modules\product\controllers;

use common\modules\product\models\search\ProductImageSearch;
use Yii;
use yii\web\Response;
use yii\db\Exception;
use yii\db\ActiveRecord;
use soft\web\SoftController;
use soft\helpers\ArrayHelper;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use Yii\base\InvalidConfigException;
use common\modules\product\models\Product;
use common\modules\galleryManager\GalleryManagerAction;
use common\modules\product\models\search\ProductSearch;
use common\modules\product\models\search\AssignProductSizeSearch;

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


    public function actionCreate()
    {
//        date_default_timezone_set('Asia/Tashkent');
//        $model = new Product([
//            'status' => Product::STATUS_ACTIVE,
//            'published_at' => date('Y-m-d H:i'),
//            'expired_at' => (new DateTime(date('Y-m-d H:i')))->modify('+3 month')->format('Y-m-d H:i')
//        ]);
//        $model->createProductSizeAssigns();
//        return $this->ajaxCrud($model)->createAction();


        $model = new Product();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $model->createProductSizeAssigns();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws Exception
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (!$model->getIsUpdatable()) {
            forbidden();
        }

        $model->product_sizes = ArrayHelper::getColumn($model->sizes, 'id');

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $transaction = Yii::$app->db->beginTransaction();

            if ($model->save(false) && $model->updateProductSizeAssign()) {
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            }

            $transaction->rollBack();
        }
        return $this->render('update', ['model' => $model]);
    }


//return $this->ajaxCrud($model)->updateAction();
//    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     * @throws \Throwable
     * @throws StaleObjectException
     * @throws ForbiddenHttpException
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
     * @return array|ActiveRecord|null
     * @throws NotFoundHttpException
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

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionProductSize($id): string
    {
        $model = $this->findModel($id);
        $query = $model->getProductSizeAssign();
        $searchModel = new AssignProductSizeSearch();
        $dataProvider = $searchModel->search($query);
        return $this->render('product-size', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionProductImage($id): string
    {
        $model = $this->findModel($id);
        $query = $model->getProductImageColor();
        $searchModel = new ProductImageSearch();
        $dataProvider = $searchModel->search($query);
        return $this->render('product-image', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
}
