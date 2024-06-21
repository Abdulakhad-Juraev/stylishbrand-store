<?php

namespace common\modules\product\models;

use common\components\CyrillicSlugBehavior;
use common\modules\galleryManager\GalleryBehavior;
use common\modules\galleryManager\GalleryImage;
use common\modules\user\models\User;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
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
 * @property string|null $image
 * @property int|null $category_id
 * @property int|null $sub_category_id
 * @property int|null $percentage
 * @property int|null $published_at
 * @property int|null $expired_at
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Category $category
 * @property User $createdBy
 * @property SubCategory $subCategory
 * @property User $updatedBy
 */
class Product extends ActiveRecord
{
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
            [['name', 'description','price'], 'string'],
            [['name', 'description'], 'required'],
            [['category_id', 'sub_category_id', 'percentage', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['published_at', 'expired_at'], 'safe'],
            [['slug'], 'string', 'max' => 1024],
            [['image'], 'file'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['sub_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubCategory::className(), 'targetAttribute' => ['sub_category_id' => 'id']],
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
                    'name', 'description'
                ],
                'languages' => $this->languages(),
            ],
            'galleryBehavior' => [
                'class' => GalleryBehavior::className(),
                'type' => 'product',
                'extension' => 'jpg',
                'directory' => Yii::getAlias('@frontend/web') . '/uploads/product/gallery',
                'url' => '/uploads/product/gallery',
                'versions' => [
                    'small' => function ($img) {
                        /** @var ImageInterface $img */
                        return $img
                            ->copy()
                            ->thumbnail(new Box(200, 200));
                    },
                    'medium' => function ($img) {
                        /** @var ImageInterface $img */
                        $dstSize = $img->getSize();
                        $maxWidth = 800;
                        if ($dstSize->getWidth() > $maxWidth) {
                            $dstSize = $dstSize->widen($maxWidth);
                        }
                        return $img
                            ->copy()
                            ->resize($dstSize);
                    },
                ]
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
            'image' => Yii::t('app', 'Image'),
            'category_id' => Yii::t('app', 'Category ID'),
            'sub_category_id' => Yii::t('app', 'Sub Category ID'),
            'percentage' => Yii::t('app', 'Foiz %'),
            'published_at' => Yii::t('app', 'Published At'),
            'expired_at' => Yii::t('app', 'Expired At'),
            'status' => Yii::t('app', 'Status'),
            'price' => Yii::t('app', 'Price'),
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

    //</editor-fold>

    /**
     * @return array
     */
    public static function map(): array
    {
        return ArrayHelper::map(self::find()->andWhere(['status' => self::STATUS_ACTIVE])->all(), 'id', 'name');
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleryImages(): \yii\db\ActiveQuery
    {
        return $this->hasMany(GalleryImage::class, ['ownerId' => 'id'])
            ->andWhere(['type' => 'product'])
            ->orderBy('rank ASC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleryImagesAsArray(): \yii\db\ActiveQuery
    {
        return $this->getGalleryImages()->asArray();
    }

    /**
     * All images of the product
     * @return array
     */
    public function getImages($type = 'preview'): array
    {
        $images = $this->galleryImagesAsArray;
        $result = [];
        foreach ($images as $image) {
            $result[] = "/uploads/product/gallery/$this->id/" . $image['id'] . "/$type.jpg";
        }
        return $result;

    }

    /**
     * Main image of the product
     * @return string
     */
    public function getImage($type = 'preview'): string
    {
        $images = $this->getImages($type);
        if (empty($images)) {
            return "/images/no-image-png";
        }
        return $images[0] ?? '';
    }
}
