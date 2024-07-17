<?php

namespace api\models;
class ProductCharacter extends \common\modules\product\models\ProductCharacter

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
            'title',
            'title',
            'category_character_id',
            'categoryCharacter',
            'with_check_icon',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryCharacter()
    {
        return $this->hasOne(CategoryCharacter::class, ['id' => 'category_character_id']);
    }
}