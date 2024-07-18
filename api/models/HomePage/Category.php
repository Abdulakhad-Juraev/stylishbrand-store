<?php

namespace api\models\HomePage;

use api\models\CategoryCharacter;
use api\models\Product;
use api\models\SubCategory;
use soft\helpers\Url;
use soft\db\ActiveQuery;

class Category extends \common\modules\product\models\Category
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
            'imageUrl',
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