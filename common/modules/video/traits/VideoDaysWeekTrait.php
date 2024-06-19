<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 19-Apr-24, 10:34
 */

namespace common\modules\video\traits;

use common\modules\video\models\VideoDaysWeek;
use soft\helpers\ArrayHelper;
use yii\db\ActiveQuery;

trait VideoDaysWeekTrait
{

    /**
     * @var
     */
    public $week_days;

    /**
     * @return ActiveQuery
     */
    public function getWeekDays()
    {
        return $this->hasMany(VideoDaysWeek::className(), ['video_id' => 'id']);
    }

    /**
     * @return bool
     */
    public function createVideoWeekDayAssigns()
    {

        $weekDays = (array)$this->week_days;

        if (empty($weekDays)) {
            return true;
        }

        foreach ($weekDays as $weekDay) {
            $videoDaysWeekAssignModel = new VideoDaysWeek();
            $videoDaysWeekAssignModel->video_id = $this->id;
            $videoDaysWeekAssignModel->week_id = $weekDay;
            $videoDaysWeekAssignModel->save();
        }
        return true;
    }

    /**
     * @return bool
     */
    public function updateVideoWeekDayAssigns()
    {
        VideoDaysWeek::deleteAll(['video_id' => $this->id]);
        return $this->createVideoWeekDayAssigns();
    }

    /**
     * @return string
     */
    public function getBadWeekDayAssign()
    {
        $weekDays = $this->weekDays;
        $badge = '';

        if ($weekDays == null) {
            return $badge;
        }

        foreach ($weekDays as $weekDay) {
            $badge .= "<span class = 'badge badge-secondary'> " . '<i class="fas fa-calendar-alt"></i> ' . $weekDay->getWeekName() . "</span> ";
        }

        return $badge;
    }

}