<?php

namespace common\modules\user\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\rbac\Item;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property int $type
 * @property string|null $description
 * @property string|null $rule_name
 * @property resource|null $data
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 */
class AuthItem extends \soft\db\ActiveRecord
{

    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function labels()
    {
        return [
            'name' => 'Name',
            'type' => 'Type',
            'description' => 'Description',
            'rule_name' => 'Rule Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }


    //</editor-fold>

    public static function types()
    {
        return [
            Item::TYPE_ROLE => 'Role',
            Item::TYPE_PERMISSION => 'Permission',
        ];
    }

    /**
     * @return AuthItem[]
     */
    public static function userRoles()
    {
        return static::find()
            ->andWhere(['type' => Item::TYPE_ROLE])
            ->andWhere(['!=', 'name', 'admin'])
            ->all();
    }

}
