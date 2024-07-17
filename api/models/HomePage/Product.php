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
            'categoryName' => function (Product $model) {
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

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public static function getMostPopularProducts()
    {
        Product::setFields([
            'slug',
            'name',
            'category_id',
            'categoryName',
            'price',
            'discount_price' => 'sum',
            'image',
        ]);
        return Product::find()->andWhere(['most_popular' => Product::STATUS_ACTIVE])->orderBy(['id' => SORT_DESC])->limit(20)->active()->all();
    }

    /**
     * @return ActiveQuery
     */
//    public function getImages()
//    {
//        return $this->hasMany(ProductImage::class, ['product_id' => 'id']);
//    }

    /**
     * @return float|int
     */
    public function getSum(): float|int
    {
        $percentage = ($this->price * $this->percentage) / 100;
        return $this->price - $percentage;
    }

    /**
     * @return \soft\db\ActiveQuery
     */
    /*  public function getAssignProductSizes()
      {
          return $this->hasMany(AssignProductSize::class, ['product_id' => 'id']);
      }*/

    /**
     * @return \soft\db\ActiveQuery
     */
//    public function getSizes()
//    {
//        return $this->hasMany(ProductSize::class, ['id' => 'size_id'])->via('assignProductSizes');
//    }

    /**
     * @return ActiveQuery
     */
//    public function getProductColor()
//    {
//        return $this->hasMany(ProductImage::class, ['product_id' => 'id']);
//    }

    /**
     * @return ActiveQuery
     */
//    public function getProductCharacters()
//    {
//        return $this->hasMany(ProductCharacter::class, ['product_id' => 'id']);
//    }


}