<?php

namespace api\controllers;


use api\models\Category;
use api\models\ProductSize;
use api\utils\MessageConst;
use common\modules\product\models\Brand;

class FilterController extends ApiBaseController
{
    /**
     * @return array
     */
    public function actionBrandName(): array
    {
        Brand::setFields(['name']);
        $brands = Brand::find()->active()->all();
        return $this->success($brands, MessageConst::GET_SUCCESS);
    }

    /**
     * @return array
     */
    public function actionCategoryName(): array
    {
        Category::setFields([
            'name',
        ]);
        $category = Category::find()->active()->all();
        return $this->success($category, MessageConst::GET_SUCCESS);
    }

    public function actionSizes()
    {
//        ProductSize::setFields([
//            'id'
//        ]);
        $query = ProductSize::find()->active()->all();
        return $this->success($query, MessageConst::GET_SUCCESS);
    }

}