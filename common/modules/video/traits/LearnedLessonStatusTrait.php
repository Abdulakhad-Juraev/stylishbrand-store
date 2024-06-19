<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 12-Apr-24, 16:08
 */

namespace common\modules\video\traits;

use common\modules\user\models\LearnedLesson;
use soft\helpers\ArrayHelper;
use Yii;

trait LearnedLessonStatusTrait
{

    /**
     * Kursni tamomlamadi
     * @var int
     */
    public static $is_completed_no = 0;

    /**
     * Kursni to'liq tamomlamadi
     * @var int
     */
    public static $is_incomplete = 1;

    /**
     * Kursni to'liq tamomladi
     * @var int
     */
    public static $is_completed_full = 2;

    /**
     * @return string[]
     */
    public static function homeWorkCompletedTypes()
    {
        return [
            self::$is_completed_no => "Vazifa bajarilmadi",
            self::$is_incomplete => "Vazifa to'liq bajarilmadi",
            self::$is_completed_full => "Dars yakunlandi!"
        ];
    }

    /**
     * @return string[]
     */
    public static function completedTypes()
    {
        return [
            self::$is_completed_no => "Dars ko'rilmadi!",
            self::$is_completed_full => "Dars yakunlandi!"
        ];
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getHomeworkCompleteId()
    {
        $learnedLesson = LearnedLesson::find()
            ->andWhere(['user_id' => user('id')])
            ->andWhere(['video_id' => $this->id])
            ->one();

        if (!$learnedLesson) {
            return self::$is_completed_no;
        }

        return $learnedLesson->is_homework_completed;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getHomeworkCompleteName()
    {
        $learnedLesson = LearnedLesson::find()
            ->andWhere(['user_id' => user('id')])
            ->andWhere(['video_id' => $this->id])
            ->one();

        if (!$learnedLesson) {
            return "Vazifa bajarilmadi";
        }

        return ArrayHelper::getValue(self::homeWorkCompletedTypes(), $learnedLesson->is_homework_completed);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getCompleteId()
    {
        $learnedLesson = LearnedLesson::find()
            ->andWhere(['user_id' => user('id')])
            ->andWhere(['video_id' => $this->id])
            ->one();

        if (!$learnedLesson) {
            return self::$is_completed_no;
        }

        return $learnedLesson->is_completed;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getCompleteName()
    {
        $learnedLesson = LearnedLesson::find()
            ->andWhere(['user_id' => user('id')])
            ->andWhere(['video_id' => $this->id])
            ->one();

        if (!$learnedLesson) {
            return "Dars ko'rilmadi";
        }

        return ArrayHelper::getValue(self::completedTypes(), $learnedLesson->is_completed);
    }
}