<?php

namespace common\modules\product\models;

use common\components\CyrillicSlugBehavior;
use common\modules\user\models\User;
use odilov\multilingual\behaviors\MultilingualBehavior;
use soft\behaviors\TimestampConvertorBehavior;
use soft\db\ActiveQuery;
use soft\db\ActiveRecord;
use soft\helpers\ArrayHelper;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $price
 * @property string|null $description
 * @property string|null $slug
 * @property int|null $country_id
 * @property int|null $category_id
 * @property int|null $sub_category_id
 * @property int|null $percentage
 * @property int|null $published_at
 * @property int|null $expired_at
 * @property int|null $status
 * @property int|null $is_stock
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Category $category
 * @property User $createdBy
 * @property SubCategory $subCategory
 * @property User $updatedBy
 * @property ProductSize[] $sizes
 */
class Product extends ActiveRecord
{

    public $product_sizes;

    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'price'], 'string'],
            [['name', 'description'], 'required'],
            [['category_id', 'sub_category_id','is_stock','most_popular', 'country_id', 'percentage', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['published_at', 'expired_at'], 'safe'],
            [['slug'], 'string', 'max' => 1024],
            [['product_sizes'], 'safe'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['sub_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubCategory::class, 'targetAttribute' => ['sub_category_id' => 'id']],
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
                    'name', 'description'
                ],
                'languages' => $this->languages(),
            ],
            [
                'class' => TimestampConvertorBehavior::class,
                'attribute' => ['published_at', 'expired_at']
            ],
            'slug' => [
                'class' => CyrillicSlugBehavior::class,
                'attribute' => 'name',
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
            'slug' => Yii::t('app', 'Slug'),
            'category_id' => Yii::t('app', 'Kategoriya'),
            'sub_category_id' => Yii::t('app', 'Pod Kategoriya'),
            'percentage' => Yii::t('app', 'Foiz %'),
            'published_at' => Yii::t('app', 'Elon qilish sanasi'),
            'expired_at' => Yii::t('app', 'Olib tashlash vaqti'),
            'status' => Yii::t('app', 'Xolat'),
            'is_stock' => Yii::t('app', 'Mavjud'),
            'price' => Yii::t('app', 'Narxi'),
            'most_popular' => Yii::t('app', 'most_popular'),
            'country_id' => Yii::t('app', 'Davlat'),
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
    public function getSubCategory()
    {
        return $this->hasOne(SubCategory::className(), ['id' => 'sub_category_id']);
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
    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }
    //</editor-fold>

    /**
     * @return array
     */
    public static function map(): array
    {
        return ArrayHelper::map(self::find()->andWhere(['status' => self::STATUS_ACTIVE])->all(), 'id', 'name');
    }






    public function createProductSizeAssigns()
    {
        $sizes = (array)$this->product_sizes;
        if (empty($sizes)) {
            return true;
        }

        foreach ($sizes as $size) {
            $productSizeModel = new AssignProductSize();
            $productSizeModel->product_id = $this->id;
            $productSizeModel->size_id = $size;
            $productSizeModel->save();
        }
        return true;
    }

    /**
     * @return ActiveQuery
     */
    public function getProductSizeAssign()
    {
        return $this->hasMany(AssignProductSize::class, ['product_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProductImageColor()
    {
        return $this->hasMany(ProductImage::class, ['product_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProductCharacters()
    {
        return $this->hasMany(ProductCharacter::class, ['product_id' => 'id']);
    }


    /**
     * @return true
     */
    public function updateProductSizeAssign()
    {
        AssignProductSize::deleteAll(['product_id' => $this->id]);
        return $this->createProductSizeAssigns();
    }


    /**
     * @return \soft\db\ActiveQuery
     */
    public function getAssignProductSizes()
    {
        return $this->hasMany(AssignProductSize::class, ['product_id' => 'id']);
    }

    /**
     * @return \soft\db\ActiveQuery
     */
    public function getSizes()
    {
        return $this->hasMany(ProductSize::class, ['id' => 'size_id'])->via('assignProductSizes');
    }


}
