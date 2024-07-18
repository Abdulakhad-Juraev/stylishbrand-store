<?php

namespace api\models\ProductDetail;

use soft\helpers\Url;


class ProductImage extends \common\modules\product\models\ProductImage
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
            'imageUrl'
        ];
    }

    /**
     * @return mixed|string|null
     */
    public function getImageUrl()
    {
        return Url::withHostInfo(parent::getImageUrl());
    }
}
