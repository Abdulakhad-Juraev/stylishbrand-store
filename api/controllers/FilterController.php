<?php

namespace api\controllers;


use api\models\Brand;
use api\models\Category;
use api\models\Product;
use api\models\ProductColor;
use api\models\ProductSize;
use api\utils\MessageConst;

class FilterController extends ApiBaseController
{

    /**
     * @return array
     */
    public function actionCategories(): array
    {
        Category::setFields([
            'id',
            'name',
        ]);
        $category = Category::find()
            ->joinWith('translations')
            ->orderBy(['category_lang.name' => SORT_ASC])->active()->all();

        return $this->success($category, MessageConst::GET_SUCCESS);
    }

    /**
     * @return array
     */
    public function actionSizes(): array
    {
        $query = ProductSize::find()->orderBy(['sort_order' => SORT_ASC])->active()->all();
        return $this->success($query, MessageConst::GET_SUCCESS);
    }

    /**
     * @return array
     */
    public function actionBrands(): array
    {
        $brands = Brand::find()->orderBy(['name' => SORT_ASC])->active()->all();
        return $this->success($brands, MessageConst::GET_SUCCESS);
    }

    /**
     * @return array
     */
    public function actionColors(): array
    {
        $colors = ProductColor::find()
            ->joinWith('translations')
            ->orderBy(['name' => SORT_ASC])
            ->active()->all();
        return $this->success($colors, MessageConst::GET_SUCCESS);
    }

    /**
     * @return array
     */
    public function actionPrices(): array
    {
        Product::setFields([
            'id',
            'price'
        ]);
        $prices = Product::find()->active()->all();
        return $this->success($prices, MessageConst::GET_SUCCESS);
    }
}