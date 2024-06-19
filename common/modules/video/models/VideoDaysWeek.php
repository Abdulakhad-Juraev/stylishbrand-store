<?php

namespace common\modules\video\models;

use common\models\User;
use soft\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "video_days_week".
 *
 * @property int $id
 * @property int|null $week_id
 * @property int|null $video_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property Video $video
 */
class VideoDaysWeek extends \soft\db\ActiveRecord
{

    /**
     * Monday
     * @var int
     */
    public static $week_day_monday = 2;

    /**
     * Tuesday
     * @var int
     */
    public static $week_day_tuesday = 3;

    /**
     * Wednesday
     * @var int
     */
    public static $week_day_wednesday = 4;

    /**
     * Thursday
     * @var int
     */
    public static $week_day_thursday = 5;

    /**
     * Friday
     * @var int
     */
    public static $week_day_friday = 6;

    /**
     * Saturday
     * @var int
     */
    public static $week_day_saturday = 7;

    /**
     * Sunday
     * @var int
     */
    public static $week_day_sunday = 8;

    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'video_days_week';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['week_id', 'video_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['video_id'], 'exist', 'skipOnError' => true, 'targetClass' => Video::className(), 'targetAttribute' => ['video_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'yii\behaviors\TimestampBehavior',
            'yii\behaviors\BlameableBehavior',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function labels()
    {
        return [
            'id' => 'ID',
            'week_id' => 'Week ID',
            'video_id' => 'Video ID',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    //</editor-fold>

    //<editor-fold desc="Relations" defaultstate="collapsed">

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideo()
    {
        return $this->hasOne(Video::className(), ['id' => 'video_id']);
    }

    //</editor-fold>

    /**
     * @return array
     */
    public static function weeks()
    {
        return [
            self::$week_day_monday => t('Monday'), //'Dushanba',
            self::$week_day_tuesday => t('Tuesday'),//'Seshanba',
            self::$week_day_wednesday => t('Wednesday'),//'Chorshanba',
            self::$week_day_thursday => t('Thursday'),//"Payshanba",
            self::$week_day_friday => t('Friday'),//"Juma",
            self::$week_day_saturday => t('Saturday'),//"Shanba",
            self::$week_day_sunday => t('Sunday') //"Yakshanba",
        ];
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getWeekName()
    {
        return ArrayHelper::getValue(self::weeks(), $this->week_id);
    }
}
