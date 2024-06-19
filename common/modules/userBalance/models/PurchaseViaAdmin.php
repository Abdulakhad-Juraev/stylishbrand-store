<?php

namespace common\modules\userBalance\models;

use common\models\User;
use common\traits\TariffBookSpecialCourseTrait;
use Yii;

/**
 * This is the model class for table "purchase_via_admin".
 *
 * @property int $id
 * @property string|null $full_name
 * @property string|null $phone
 * @property int|null $user_id
 * @property int|null $type_id
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $owner_id
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property User $user
 */
class PurchaseViaAdmin extends \soft\db\ActiveRecord
{
    use TariffBookSpecialCourseTrait;

    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_via_admin';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'type_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'owner_id'], 'integer'],
            [['full_name', 'phone'], 'string', 'max' => 255],
            [['full_name', 'phone'], 'required'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function labels()
    {
        return [
            'id' => 'ID',
            'full_name' => 'F I SH',
            'phone' => 'Telefon raqam',
            'user_id' => 'Admin',
            'type_id' => 'Sababi',
            'status' => 'Tanishildimi ?',
//            'created_by' => 'Created By',
//            'updated_by' => 'Updated By',
//            'created_at' => 'Created At',
//            'updated_at' => 'Updated At',
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
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    //</editor-fold>
}
