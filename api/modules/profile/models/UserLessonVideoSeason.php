<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 23-Apr-24, 14:17
 */

namespace api\modules\profile\models;

class UserLessonVideoSeason extends \common\modules\video\models\UserLessonVideoSeason
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
            'parent_video_id',
            'copmleted_count',
            'lesson_count',
            'completed_percent',
        ];

    }

}