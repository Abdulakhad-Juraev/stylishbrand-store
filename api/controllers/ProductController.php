<?php

namespace api\controllers;


use Yii;
use api\models\ProductDetail\Product;
use api\utils\MessageConst;
use yii\web\NotFoundHttpException;
use api\models\ProductDetail\RecommendedProduct;

class ProductController extends ApiBaseController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * @throws NotFoundHttpException
     */
    public function actionDetail($slug)
    {

        $product = Product::findOne(['slug' => $slug]);

        if ($product === null) {
            throw new NotFoundHttpException(MessageConst::NOT_FOUND_MESSAGE);
        }
        $data = [
            'products' => $product,
            'recommendedProducts' => $this->recProduct($product)
        ];
        return $this->success([$data], MessageConst::GET_SUCCESS);
    }


    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionFavorites(): array
    {
        $slugs = Yii::$app->request->get('slug');
        if (empty($slugs)) {
            throw new NotFoundHttpException(MessageConst::NOT_FOUND_MESSAGE);
        }

        Product::setFields([
            "slug",
            "name",
            'category_id',
            'categoryName' => function ($model) {
                return $model->category->name ?? '';
            },
            'price',
            'discount_price' => 'sum',
            'image',
        ]);

        $products = Product::find()
            ->where(['in', 'slug', $slugs])
            ->all();

        return $this->success($products, MessageConst::GET_SUCCESS);
    }

    /**
     * @param $product
     * @return array
     */
    private function recProduct($product): array
    {
        return RecommendedProduct::find()
            ->andWhere(['brand_id' => $product->brand_id])
            ->andWhere(['!=', 'id', $product->id])
            ->orderBy(['id' => SORT_DESC])
            ->limit(4)
            ->all();
    }

}