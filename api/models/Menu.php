<?php

namespace api\models;

use soft\helpers\Url;

class Menu extends \common\modules\banner\models\Menu
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
            'phone',
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