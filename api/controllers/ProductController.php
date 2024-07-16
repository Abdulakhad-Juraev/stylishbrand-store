<?php

namespace api\controllers;


use api\models\Product;
use api\utils\MessageConst;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

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
        Product::setFields([
            'slug',
            'name',
            'description',
            'category_id',
            'category_id' => function (Product $model) {
                return $model->category->name ?? '';
            },
            'sub_category_id',
            'sub_category_id' => function ($model) {
                return $model->subCategory->name ?? '';
            },
            'percentage',
            'published_at',
            'expired_at',
            'price',
            'priceFormat' => function ($model) {
                return as_sum($model->price);
            },
            'discount_price' => 'sum',
            'brand_id',
            'brand_id' => function ($model) {
                return $model->brand->name ?? '';
            },
            'content',
            'country_id',
            'country_id' => function ($model) {
                return $model->country->name ?? '';
            },
            'is_stock',
            'most_popular',
            'image',
            'images'
            ,
            // SIZES
            'sizes',
            'productImageColor',
            'productCharacters',

        ]);

        if ($product === null) {
            throw new NotFoundHttpException(MessageConst::NOT_FOUND_MESSAGE);
        }


        return $this->success($product, MessageConst::GET_SUCCESS);
    }


    /**
     * @return array
     */
    public function actionIndex()
    {
        $products = Product::find()->orderBy(['id' => SORT_ASC])->active();

        $productsDataProvider = new ActiveDataProvider([
            'query' => $products
        ]);
        return $this->success($productsDataProvider, MessageConst::GET_SUCCESS);
    }


    /**
     * @return array
     */
    public function actionMostPopular(): array
    {
        Product::setFields([
            'slug',
            'name',
            'price',
            'category_id',
        ]);
        $category = Product::find()->andWhere(['most_popular' => Product::STATUS_ACTIVE])->orderBy(['id' => SORT_ASC])->active()->all();
        return $this->success($category, MessageConst::GET_SUCCESS);
    }


}