<?php

namespace api\models;

use soft\helpers\Url;
use soft\db\ActiveQuery;

class Banner extends \common\modules\banner\models\Banner
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
            'title_uz',
            'title_ru',
            'description',
            'image'=>'imageUrl',
            'count',
            'button_url',
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