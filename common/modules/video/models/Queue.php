<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 28-Mar-24, 22:29
 */

namespace common\modules\video\models;

use soft\db\ActiveRecord;

class Queue extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%queue}}';
    }
}