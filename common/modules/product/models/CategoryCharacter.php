<?php

namespace common\modules\product\models;

use common\modules\user\models\User;
use odilov\multilingual\behaviors\MultilingualBehavior;
use soft\db\ActiveQuery;
use soft\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "category_character".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $category_id
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Category $category
 * @property User $createdBy
 * @property User $updatedBy
 * @property ProductCharacter[] $productCharacters
 */
class CategoryCharacter extends \soft\db\ActiveRecord
{
    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category_character';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['name'], 'required'],
            [['category_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
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
                    'name'
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
            'category_id' => Yii::t('app', 'Category ID'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
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
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCharacters()
    {
        return $this->hasMany(ProductCharacter::className(), ['category_character_id' => 'id']);
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
