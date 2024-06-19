<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 15-Apr-24, 12:52
 */

namespace api\modules\profile\models;

class UserDevice extends \common\modules\user\models\UserDevice
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
            'device_id',
            'device_name',
            'created_at' => function (UserDevice $model) {
                return date('d.m.Y H:i', $model->created_at);
            }
        ];

    }
}