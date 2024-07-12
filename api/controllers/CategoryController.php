<?php

namespace api\controllers;

use api\models\Category;
use api\models\Product;
use api\utils\MessageConst;
use yii\db\Expression;

class CategoryController extends ApiBaseController
{
    /**
     * @return array
     */
    public function actionIndex()
    {
        return $this->success(Category::find()->orderBy(['id' => SORT_ASC])->active()->all(), MessageConst::GET_SUCCESS);
    }


    /**
     * @return array
     */
    public function actionHomeHeader(): array
    {
        $category = Category::find()->orderBy(['id' => SORT_ASC])->active()->all();
        return $this->success($category, MessageConst::GET_SUCCESS);
    }

    /**
     * @return array
     */
    public function actionPageList(): array
    {
        Category::setFields([
            'id',
            'name',
            'subCategories',
            'image' => 'imageUrl',
            'products',
        ]);

        Product::setFields([
            'id',
            'name',
            'price' => 'sum',
        ]);
        $category = Category::find()->orderBy(['id' => SORT_ASC])->active()->all();
        return $this->success([$category], MessageConst::GET_SUCCESS);
    }


    /**
     * @return array
     */
    public function actionRandoms(): array
    {
        $category = Category::find()->orderBy(new Expression('rand()'))->limit(3)->active()->all();
        return $this->success($category, MessageConst::GET_SUCCESS);
    }



}