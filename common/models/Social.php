<?php

namespace common\models;

use mohorev\file\UploadImageBehavior;
use Yii;

/**
 * This is the model class for table "social".
 *
 * @property int $id
 * @property string|null $url
 * @property string|null $image
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class Social extends \soft\db\ActiveRecord
{
    //<editor-fold desc="Parent" defaultstate="collapsed">

    const BASE_ORIGINAL_URL = '/uploads/podcasts/original';

    /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        return 'social';
    }

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
        return [

            [['url','image'], 'required'],
            [['status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['url'], 'string', 'max' => 255],
            [['image'], 'file'],
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
            'image' => [
                'class' => UploadImageBehavior::class,
                'attribute' => 'image',
                'scenarios' => ['default'],
                'path' => '@frontend/web/uploads/images/social/{id}',
                'url' => '/uploads/images/social/{id}',
                'deleteOriginalFile' => true,
                'thumbs' => [
                    'preview' => ['width' => 1440],
                ],
            ],
        ];
    }

    /**
    * {@inheritdoc}
    */
    public function labels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url Manzili',
            'image' => 'Rasm',
//            'status' => 'Xolati',
//            'created_by' => 'Kiritildi',
//            'updated_by' => 'Updated By',
//            'created_at' => 'Kiritilgan vaqti',
//            'updated_at' => 'Tahrirlangan vaqti',
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

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->image ? $this->getBehavior('image')->getThumbUploadUrl('image', 'preview') : '';
    }
}
