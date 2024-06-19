<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 25-Apr-24, 15:17
 */

namespace api\modules\profile\models;

use Yii;

class CourseDailyTaskStatistic extends \common\modules\user\models\CourseDailyTaskStatistic
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
            'course_id',
            'user_id',
            'did_you_run_percent' => function (CourseDailyTaskStatistic $model) {
                return floatval(Yii::$app->formatter->asDecimal($model->did_you_run_percent, 2));
            },
            'did_you_read_percent' => function (CourseDailyTaskStatistic $model) {
                return floatval(Yii::$app->formatter->asDecimal($model->did_you_read_percent, 2));
            },
            'did_you_meet_people_percent' => function (CourseDailyTaskStatistic $model) {
                return floatval(Yii::$app->formatter->asDecimal($model->did_you_meet_people_percent, 2));
            },
            'did_you_present_yourself_percent' => function (CourseDailyTaskStatistic $model) {
                return floatval(Yii::$app->formatter->asDecimal($model->did_you_present_yourself_percent, 2));
            },
            'getting_started_with_person' => function (CourseDailyTaskStatistic $model) {
                return floatval(Yii::$app->formatter->asDecimal((($model->did_you_present_yourself_percent + $model->did_you_meet_people_percent) / 2), 2));
            },
            'total_percent' => function (CourseDailyTaskStatistic $model) {
                return floatval(Yii::$app->formatter->asDecimal($model->total_percent, 2));
            }
        ];

    }
}