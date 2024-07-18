<?php

namespace ban;

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
        return $this->hasOne(\api\models\ProductDetail\ProductImage::class, ['product_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(\api\models\ProductDetail\ProductImage::class, ['product_id' => 'id']);
    }


    /**
     * @return \soft\db\ActiveQuery
     */
    public function getAssignProductSizes()
    {
        return $this->hasMany(\api\models\ProductDetail\AssignProductSize::class, ['product_id' => 'id']);
    }

    /**
     * @return \soft\db\ActiveQuery
     */
    public function getSizes()
    {
        return $this->hasMany(\api\models\ProductDetail\ProductSize::class, ['id' => 'size_id'])->via('assignProductSizes');
    }

    /**
     * @return ActiveQuery
     */
    public function getProductColor()
    {
        return $this->hasMany(\api\models\ProductDetail\ProductImageColor::class, ['product_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProductCharacters()
    {
        return $this->hasMany(\api\models\ProductDetail\ProductCharacter::class, ['product_id' => 'id']);
    }


}