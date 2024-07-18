<?php

namespace ban;

class CategoryCharacter extends \common\modules\product\models\CategoryCharacter
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
            'productCharacters'
        ];


    }

    public function getProductCharacters()
    {
        return $this->hasMany(ProductCharacter::class, ['category_character_id' => 'id']);
    }

}