<?php

namespace common\modules\video\models;

use common\models\User;
use mohorev\file\UploadImageBehavior;
use odilov\multilingual\behaviors\MultilingualBehavior;
use soft\behaviors\UploadBehavior;
use Yii;

/**
 * This is the model class for table "homework".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $file_url
 * @property int|null $video_id
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property Video $video
 */
class Homework extends \soft\db\ActiveRecord
{
    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'homework';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['video_id', 'title'], 'required'],
            [['video_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 1024],
            [['file_url'], 'file', 'maxSize' => 1024 * 1024 * 10],
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
            'file_url' => [
                'class' => UploadBehavior::class,
                'attribute' => 'file_url',
                'scenarios' => ['default'],
                'path' => '@frontend/web/uploads/homework/{id}',
                'url' => '/uploads/homework/{id}',
            ],
            'multilingual' => [
                'class' => MultilingualBehavior::class,
                'attributes' => ['title'],
                'languages' => $this->languages(),
            ],

        ];
    }


    /**
     * @return \soft\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()->multilingual();
    }

    /**
     * {@inheritdoc}
     */
    public function labels()
    {
        return [
            'id' => 'ID',
            'title' => 'Qisqa izoh',
            'file_url' => 'Fayl',
            'video_id' => 'Video',
//            'status' => 'Status',
//            'created_by' => 'Created By',
//            'updated_by' => 'Updated By',
//            'created_at' => 'Created At',
//            'updated_at' => 'Updated At',/
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
     * @return mixed
     */
    public function getFileUrl()
    {
        return $this->getBehavior('file_url')->getUploadUrl('file_url');
    }


}
