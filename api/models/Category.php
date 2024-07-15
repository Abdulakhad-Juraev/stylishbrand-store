<?php

namespace api\models;

use soft\helpers\Url;
use soft\db\ActiveQuery;
use yii\db\Expression;

class Category extends \common\modules\product\models\Category
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
            'name',
            'image' => 'imageUrl',
        ];


    }

    /**
     * @return mixed|string|null
     */
    public function getImageUrl()
    {
        return Url::withHostInfo(parent::getImageUrl());
    }

    /**
     * @return ActiveQuery
     */
    public function getSubCategories()
    {
        return $this->hasMany(SubCategory::class, ['category_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCharacters()
    {
        return $this->hasMany(CategoryCharacter::class, ['category_id' => 'id']);
    }

    public function getProducts()
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }


    /**
     * @param $id
     * @return bool|mixed|string|null
     */
    public function getMaxPrice($id)
    {
        return $this->getProducts()->max('price');
    }

    /**
     * @param $id
     * @return bool|mixed|string|null
     */
    public function getMinPrice($id): mixed
    {
        return $this->getProducts()->min('price');
    }

    public function getInterestingCategories($category_id)
    {
        return Category::find()
            ->andWhere(['!=', 'id', $category_id])
            ->orderBy(new Expression('rand()'))
            ->limit(3)
            ->active()
            ->all();
    }
}