<?php

namespace common\models;

use mohorev\file\UploadImageBehavior;
use odilov\multilingual\behaviors\MultilingualBehavior;
use soft\db\ActiveQuery;
use Yii;

/**
 * This is the model class for table "contact_info".
 *
 * @property int $id
 * @property string|null $logo
 * @property string|null $phone
 * @property string|null $support_phone
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $support_description
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class ContactInfo extends \soft\db\ActiveRecord
{
    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contact_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone'], 'required'],
            [['status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['phone', 'support_phone', 'support_description'], 'string', 'max' => 255],
            [['logo'], 'image', 'maxSize' => 1024 * 1024 * 5],
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
                'attribute' => 'logo',
                'scenarios' => ['default'],
                'path' => '@frontend/web/uploads/images/contact_info/{id}',
                'url' => '/uploads/images/contact_info/{id}',
                'deleteOriginalFile' => true,
                'thumbs' => [
                    'preview' => ['width' => 1440],
                ],
            ],
            'multilingual' => [
                'class' => MultilingualBehavior::class,
                'attributes' => [
                    'support_description',
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
            'id' => 'ID',
            'logo' => 'Tizim logosi',
            'phone' => 'Asosiy telefon raqam',
            'support_phone' => 'Support tel raqami',
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
        return $this->logo ? $this->getBehavior('image')->getThumbUploadUrl('logo', 'preview') : '';
    }
}
