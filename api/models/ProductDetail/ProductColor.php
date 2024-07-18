<?php

namespace api\models\ProductDetail;
class ProductColor extends \common\modules\product\models\ProductColor

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
            'color',
            'name',
        ];
    }

}