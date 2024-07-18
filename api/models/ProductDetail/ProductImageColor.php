<?php

namespace api\models\ProductDetail;

class ProductImageColor extends \common\modules\product\models\ProductImage
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
            'color_id',
            'colorName'=>function ($model) {
                return $model->color->name ?? '';
            },
            'colorCode'=>function ($model) {
                return $model->color->color ?? '';
            },
        ];
    }
}
