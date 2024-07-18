<?php

namespace ban;

use common\modules\banner\traits\BannerTypeTrait;
use soft\helpers\Url;

class Banner extends \common\modules\banner\models\Banner
{
    use BannerTypeTrait;
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
            'description',
            'imageUrl',
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