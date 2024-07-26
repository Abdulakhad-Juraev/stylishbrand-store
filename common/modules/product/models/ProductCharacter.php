<?php

namespace common\modules\product\models;

use common\modules\user\models\User;
use odilov\multilingual\behaviors\MultilingualBehavior;
use soft\db\ActiveQuery;
use soft\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "product_character".
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $product_id
 * @property int|null $category_character_id
 * @property int|null $status
 * @property int|null $with_check_icon
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property CategoryCharacter $categoryCharacter
 * @property User $createdBy
 * @property User $updatedBy
 */
class ProductCharacter extends \soft\db\ActiveRecord
{
    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_character';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'string'],
            [['title'], 'required'],
            [['category_character_id', 'product_id', 'with_check_icon', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['category_character_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoryCharacter::className(), 'targetAttribute' => ['category_character_id' => 'id']],
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
                'attributes' => [
                    'title'
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
            'category_character_id' => Yii::t('app', 'Xususiyatlar kategoriyasi'),
            'product_id' => Yii::t('app', 'Mahsulot'),
            'status' => Yii::t('app', 'Status'),
            'with_check_icon' => Yii::t('app', 'Belgi qo\'shish'),
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
    public function getCategoryCharacter()
    {
        return $this->hasOne(CategoryCharacter::className(), ['id' => 'category_character_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

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
     * @return array
     */
    public static function map()
    {
        return ArrayHelper::map(self::find()->andWhere(['status' => self::STATUS_ACTIVE])->all(), 'id', 'name');
    }
}
