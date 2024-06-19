<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 12-Apr-24, 22:27
 */

namespace common\modules\video\traits;

use common\modules\user\models\UserHomework;
use common\modules\video\models\Homework;
use soft\db\ActiveQuery;

/**
 * @property Homework $homeWorks
 * @property UserHomework $userHomeWorks
 */
trait HomeWorkTrait
{
//    public static $is)
    /**
     * @return ActiveQuery
     */
    public function getHomeWorks()
    {
        return $this->hasMany(Homework::className(), ['video_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserHomeWorks()
    {
        return $this->hasMany(UserHomework::className(), ['video_id' => 'id']);
    }
}