<?php

namespace ban;

use common\models\User;
use common\modules\product\models\Product;
use soft\db\ActiveQuery;
use soft\helpers\Url;

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
 *
 * @property User $createdBy
 * @property User $updatedBy
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
            'id',
            'color_id',
            'color',
            'imageUrl'
        ];
    }
    public function getColor(): ActiveQuery
    {
        return $this->hasOne(ProductColor::class, ['id' => 'color_id']);
    }
    /**
     * @return mixed|string|null
     */
    public function getImageUrl()
    {
        return $this->image  ? Url::withHostInfo(parent::getImageUrl()) : '';
    }

    /**
     * @return ActiveQuery
     */
    public function getProduct(): \soft\db\ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}
