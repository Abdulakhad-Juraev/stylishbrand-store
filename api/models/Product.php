<?php

namespace api\models;
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
            'sub_category_id',
            'percentage',
            'published_at',
            'expired_at',
            'price'=>'sum',
            'brand_id',
            'content',
            'country_id',
            'is_stock',
            'most_popular',
            'image'
        ];
    }

    public function getImage()
    {
        return $this->hasMany(ProductImage::class, ['product_id' => 'id']);
    }

    /**
     * @return float|int
     */
    public function getSum(): float|int
    {
        return ($this->price * $this->percentage) / 100;
    }
}