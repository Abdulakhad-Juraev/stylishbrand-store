<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 23-Apr-24, 16:58
 */

namespace api\modules\profile\models;

class LearnedLesson extends \common\modules\user\models\LearnedLesson
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
            'video_id',
            'is_completed',
            'is_homework_completed',
            'parent_id',
            'season_id'
        ];

    }
}