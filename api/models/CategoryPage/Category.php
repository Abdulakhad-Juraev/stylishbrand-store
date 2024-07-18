<?php

namespace api\models\CategoryPage;

use soft\helpers\Url;
use yii\db\Expression;
use yii\db\ActiveRecord;

class       Category extends \common\modules\product\models\Category
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
            'imageUrl',
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
     * @param $category_id
     * @return array|ActiveRecord[]
     */
    public function getInterestingCategories($categoryId)
    {
        return Category::find()
            ->andWhere(['!=', 'id', $categoryId])
            ->orderBy(new Expression('rand()'))
            ->limit(3)
            ->active()
            ->all();
    }


    public function getSubCategories()
    {
        return $this->hasMany(SubCategory::class, ['category_id' => 'id']);
    }

    public function getProducts()
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }
}