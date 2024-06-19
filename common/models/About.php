<?php

namespace common\models;

use mohorev\file\UploadImageBehavior;
use odilov\multilingual\behaviors\MultilingualBehavior;
use soft\db\ActiveQuery;
use Yii;

/**
 * This is the model class for table "about".
 *
 * @property int $id
 * @property string|null $description
 * @property string|null $image
 * @property string|null $phone_number
 * @property string|null $telegram_link
 * @property string|null $email
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class About extends \soft\db\ActiveRecord
{
    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        return 'about';
    }

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
        return [
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['phone_number', 'telegram_link', 'email'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1024],
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
                'path' => '@frontend/web/uploads/images/about/{id}',
                'url' => '/uploads/images/about/{id}',
                'deleteOriginalFile' => true,
                'thumbs' => [
                    'preview' => ['width' => 1440],
                ],
            ],
            'multilingual' => [
                'class' => MultilingualBehavior::class,
                'attributes' => [
                    'description'
                ],
                'languages' => $this->languages(),
            ],
        ];
    }

    /**
     * @return ActiveQuery
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
            'id' => Yii::t('app', 'ID'),
            'description' => Yii::t('app', 'Description'),
            'image' => Yii::t('app', 'Image'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'telegram_link' => Yii::t('app', 'Telegram Link'),
            'email' => Yii::t('app', 'Email'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
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
