<?php

namespace api\models\HomePage;

use api\models\HomePage\ProductImage;
use soft\db\ActiveQuery;

class Product extends \common\modules\product\models\Product
{
    /**
     * @return array|string[]
     */
    public function fields()
    {
        $key = self::generateFieldParam();

        if (isset(self::$fields[$key])) {
            return self::$fields[$key];
        }

        return [
//            'id',
            'slug',
            'name',
            'description',
            'category_id',
            'categoryName' => function ($model) {
                return $model->category->name ?? '';
            },
            'sub_category_id',
            'percentage',
            'published_at',
            'expired_at',
            'price',
            'discount_price' => 'sum',
            'brand_id',
            'content',
            'country_id',
            'is_stock',
            'most_popular',
            'image',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(ProductImage::class, ['product_id' => 'id']);
    }
}