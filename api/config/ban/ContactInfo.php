<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 03-May-24, 11:42
 */

namespace ban;

use soft\helpers\Url;

class ContactInfo extends \common\models\ContactInfo
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
            'support_phone',
            'imageUrl',
            'support_description',
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