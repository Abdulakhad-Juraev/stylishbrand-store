<?php

namespace common\modules\video\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "user_lesson_video_season".
 *
 * @property int $id
 * @property int|null $parent_video_id
 * @property int|null $user_id
 * @property int|null $season_id
 * @property int|null $copmleted_count
 * @property int|null $lesson_count
 * @property float|null $completed_percent
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class UserLessonVideoSeason extends \soft\db\ActiveRecord
{
    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_lesson_video_season';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_video_id', 'user_id', 'season_id',
                'copmleted_count', 'lesson_count',
                'created_by', 'updated_by', 'created_at', 'updated_at'
            ], 'integer'],
            [['completed_percent'], 'number'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
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
            'parent_video_id' => 'Parent Video ID',
            'user_id' => 'User ID',
            'season_id' => 'Season ID',
            'copmleted_count' => 'Copmleted Count',
            'lesson_count' => 'Lesson Count',
            'completed_percent' => 'Completed Percent',
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

    //</editor-fold>
}
