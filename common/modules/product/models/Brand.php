<?php

namespace common\modules\product\models;

use soft\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $status
 * @property Product[] $products
 */
class Brand extends \soft\db\ActiveRecord
{
    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        return 'brand';
    }

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
    * {@inheritdoc}
    */
    public function labels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
    //</editor-fold>

    //<editor-fold desc="Relations" defaultstate="collapsed">

    
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['brand_id' => 'id']);
    }

    //</editor-fold>

    /**
     * @return array
     */
    public static function map(): array
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }
}
