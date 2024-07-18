<?php

namespace api\models\ProductDetail;
use api\models\CategoryPage\AssignProductSize;
use soft\db\ActiveQuery;

class RecommendedProduct extends Product
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
            'slug',
            'name',
            'category_id',
            'categoryName' => function (Product $model) {
                return $model->category->name ?? '';
            },
            'sizes',
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
}