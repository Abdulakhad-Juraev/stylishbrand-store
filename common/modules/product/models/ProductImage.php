<?php

namespace common\modules\product\models;

use common\models\User;
use mohorev\file\UploadImageBehavior;
use soft\db\ActiveQuery;
use Yii;

/**
 * This is the model class for table "product_image".
 *
 * @property int $id
 * @property int|null $color_id
 * @property int|null $status
 * @property string|null $image
 * @property int|null $product_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class ProductImage extends \soft\db\ActiveRecord
{
    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['color_id', 'status', 'product_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['image'], 'file'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
            [['product_id', 'color_id'], 'unique', 'targetAttribute' => ['product_id', 'color_id'], 'skipOnEmpty' => false, 'skipOnError' => false, 'message' => 'The combination of Product ID and Color ID has already been taken.'],
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
                'path' => '@frontend/web/uploads/images/product-image/{id}',
                'url' => '/uploads/images/product-image/{id}',
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
            'id' => Yii::t('app', 'ID'),
            'color_id' => Yii::t('app', 'Ranglar'),
            'image' => Yii::t('app', 'Image'),
            'product_id' => Yii::t('app', 'Mahsulot'),
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

    /**
     * @return ActiveQuery
     */
    public function getProduct(): \soft\db\ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getColor(): ActiveQuery
    {
        return $this->hasOne(ProductColor::class, ['id' => 'color_id']);
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
