<?php

namespace api\controllers;

use api\utils\MessageConst;
use api\models\HomePage\Banner;
use api\models\HomePage\Category;
use api\models\HomePage\Product;

class BannerController extends ApiBaseController
{
    /**
     * @return array
     */
    public function actionBanners(): array
    {
        $top_banner = Banner::find()->where(['type' => Banner::$top_banner])->orderBy(['count' => SORT_ASC])->active()->one();
        $middle_banner = Banner::find()->where(['type' => Banner::$middle_banner])->orderBy(['count' => SORT_ASC])->active()->one();
        $bottom_banner = Banner::find()->where(['type' => Banner::$bottom_banner])->orderBy(['count' => SORT_ASC])->active()->one();

        $data = [
            'topBanner' => $top_banner,
            'middleBanner' => $middle_banner,
            'bottomBanner' => $bottom_banner,
        ];
        return $this->success($data, MessageConst::GET_SUCCESS);
    }

    /**
     * @return array
     */
    public function actionHomeHeaderCategory(): array
    {
        $home_header_category = Category::find()->orderBy(['sort_order' => SORT_ASC])->active()->limit(4)->all();
        return $this->success($home_header_category, MessageConst::GET_SUCCESS);
    }

    /**
     * @return array
     */
    public function actionMostPopularProducts(): array
    {
        Product::setFields([
            'slug',
            'name',
            'category_id',
            'categoryName',
            'categoryName' => function ($model) {
                return $model->category->name ?? '';
            },
            'price',
            'discount_price' => 'sum',
            'image',
        ]);
        $product = Product::find()->andWhere(['most_popular' => Product::STATUS_ACTIVE])->orderBy(['id' => SORT_DESC])->limit(20)->active()->all();
        return $this->success($product, MessageConst::GET_SUCCESS);
    }

    /**
     * @return array
     */
    public function actionRecCategoryFirst(): array
    {

        $category1 = Category::find()
            ->where(['home_page' => Category::STATUS_ACTIVE])
            ->orderBy(['id' => SORT_DESC])
            ->active()
            ->one();

        Product::setFields([
            'slug',
            'name',
            'category_id',
            'categoryName' => function ($model) {
                return $model->category ? $model->category->name : '';
            },
            'price',
            'discount_price' => 'sum',
            'image',
        ]);
        $products = Product::find()
            ->andWhere(['category_id' => $category1->id])
            ->orderBy(['id' => SORT_DESC])
            ->active()
            ->all();

        return $this->success($products, MessageConst::GET_SUCCESS);
    }

    /**
     * @return array
     */
    public function actionRecCategorySecond(): array
    {

        $category1 = Category::find()
            ->where(['home_page' => Category::STATUS_ACTIVE])
            ->orderBy(['id' => SORT_DESC])
            ->active()
            ->offset(1)
            ->one();

        Product::setFields([
            'slug',
            'name',
            'category_id',
            'categoryName' => function ($model) {
                return $model->category ? $model->category->name : '';
            },
            'price',
            'discount_price' => 'sum',
            'image',
        ]);
        $products = Product::find()
            ->andWhere(['category_id' => $category1->id])
            ->orderBy(['id' => SORT_DESC])
            ->active()
            ->all();

        return $this->success($products, MessageConst::GET_SUCCESS);
    }

}