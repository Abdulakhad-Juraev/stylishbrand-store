<?php

namespace api\models\CategoryPage;

use soft\db\ActiveQuery;
use soft\helpers\Url;
use yii\db\Expression;
use yii\db\ActiveRecord;

class       Category extends \common\modules\product\models\Category
{
    /**
     * @return array|string[]
     */
    public function fields(): array
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
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return Url::withHostInfo(parent::getImageUrl());
    }


    /**
     * @param $categoryId
     * @return array|ActiveRecord[]
     */
    public function getInterestingCategories($categoryId): array
    {
        return Category::find()
            ->andWhere(['!=', 'id', $categoryId])
            ->orderBy(new Expression('rand()'))
            ->limit(3)
            ->active()
            ->all();
    }


    public function getSubCategories(): ActiveQuery
    {
        return $this->hasMany(SubCategory::class, ['category_id' => 'id']);
    }

    public function getProducts(): ActiveQuery
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }

}