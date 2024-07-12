<?php

namespace api\controllers;


use api\models\Product;
use api\utils\MessageConst;
use yii\data\ActiveDataProvider;

class ProductController extends ApiBaseController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

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
        $category = Product::find()->andWhere(['most_popular'=>Product::STATUS_ACTIVE])->orderBy(['id' => SORT_ASC])->active()->all();
        return $this->success($category, MessageConst::GET_SUCCESS);
    }
}