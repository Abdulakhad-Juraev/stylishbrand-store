<?php

namespace api\models\ProductDetail;

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
            'with_check_icon',
//            'categoryCharacter'
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