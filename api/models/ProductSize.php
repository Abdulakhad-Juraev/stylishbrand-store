<?php

namespace api\models;
class ProductSize extends \common\modules\product\models\ProductSize
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
        ];
    }

}