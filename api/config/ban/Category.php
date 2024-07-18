<?php

namespace ban;

use soft\db\ActiveQuery;
use soft\helpers\Url;

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
    public function getCharacters()
    {
        return $this->hasMany(CategoryCharacter::class, ['category_id' => 'id']);
    }

    public function getProducts()
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }

}