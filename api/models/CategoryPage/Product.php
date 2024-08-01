<?php

namespace api\models\CategoryPage;

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
            'category_id',
            'categoryName' => function ($model) {
                return $model->category->name ?? '';
            },
            'price',
            'discount_price' => 'sum',
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


    public function getProductsByColor()
    {
        return $this->hasMany(ProductImage::class, ['product_id' => 'id']);
    }
}