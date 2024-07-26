<?php

namespace api\models\ProductDetail;

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
            'id',
            'slug',
            'name',
            'description',
            'category_id',
            'categoryName' => function (Product $model) {
                return $model->category->name ?? '';
            },
            'sub_category_id',
            'sub_category_id' => function ($model) {
                return $model->subCategory->name ?? '';
            },

            'priceFormat' => function ($model) {
                return as_sum($model->price);
            },
            'discount_price' => 'sum',
            'brand_id',
            'brandName' => function ($model) {
                return $model->brand->name ?? '';
            },
            'content',
            'country_id',
            'country_id' => function ($model) {
                return $model->country->name ?? '';
            },
            'is_stock',
            'image',
            'images',
            'sizes',
            'productColors',
//            'categoryCharacter',
//            'productCharacters',
//            'categoryCharacters'
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(ProductImage::class, ['product_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(ProductImage::class, ['product_id' => 'id']);
    }

    /**
     * @return \soft\db\ActiveQuery
     */
    public function getAssignProductSizes()
    {
        return $this->hasMany(AssignProductSize::class, ['product_id' => 'id']);
    }

    /**
     * @return \soft\db\ActiveQuery
     */
    public function getSizes()
    {
        return $this->hasMany(ProductSize::class, ['id' => 'size_id'])->via('assignProductSizes');
    }

    /**
     * @return ActiveQuery
     */
    public function getProductColors()
    {
        return $this->hasMany(ProductImageColor::class, ['product_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProductCharacters()
    {
        return $this->hasMany(ProductCharacter::class, ['product_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCategoryCharacters()
    {
        return $this->hasMany(CategoryCharacter::class, ['id' => 'category_character_id'])->via('productCharacters');
    }
}