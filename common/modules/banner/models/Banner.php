<?php

namespace common\modules\banner\models;

use common\modules\banner\traits\BannerTypeTrait;
use common\modules\product\models\Category;
use common\modules\user\models\User;
use mohorev\file\UploadImageBehavior;
use odilov\multilingual\behaviors\MultilingualBehavior;
use Yii;

/**
 * This is the model class for table "banner".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $image
 * @property int|null $count
 * @property string|null $button_url
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $type
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class Banner extends \soft\db\ActiveRecord
{
    use BannerTypeTrait;

    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banner';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'string'],
            [['title', 'description'], 'required'],
            [['image'], 'image', 'maxSize' => 1024 * 1024 * 10],
            [['count', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'type'], 'integer'],
            [['button_url'], 'string', 'max' => 1024],
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
            'multilingual' => [
                'class' => MultilingualBehavior::class,
                'attributes' => ['title', 'description'],
                'languages' => $this->languages(),
            ],
            'image' => [
                'class' => UploadImageBehavior::class,
                'attribute' => 'image',
                'scenarios' => ['default'],
                'path' => '@frontend/web/uploads/images/banner/{id}',
                'url' => '/uploads/images/banner/{id}',
                'deleteOriginalFile' => true,
                'thumbs' => [
                    'preview' => ['width' => 1440],
                ],
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
            'id' => Yii::t('app', 'ID'),
            'image' => Yii::t('app', 'Image'),
            'count' => Yii::t('app', 'Tartib raqami'),
            'button_url' => Yii::t('app', 'Sahifaga'),
            'status' => Yii::t('app', 'Xolat'),
            'type' => Yii::t('app', 'Joylashuvi'),
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

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'button_url']);
    }
    //</editor-fold>

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->getBehavior('image')->getThumbUploadUrl('image', 'preview');
    }

}
