<?php

namespace api\models\HomePage;

use soft\helpers\Url;
use Yii;

/**
 * This is the model class for table "product_image".
 *
 * @property int $id
 * @property int|null $color_id
 * @property int|null $status
 * @property string|null $image
 * @property int|null $product_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 */
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
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return Url::withHostInfo(parent::getImageUrl());
    }
}
