<?php

namespace ban;
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
        ];
    }
}