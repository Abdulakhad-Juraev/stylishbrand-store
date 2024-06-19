<?php

namespace common\modules\user\models;

use common\modules\order\traits\PaymentTypeTrait;
use common\modules\video\models\Video;
use common\traits\TariffBookSpecialCourseTrait;
use soft\db\ActiveQuery;
use soft\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "user_payment".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $amount
 * @property int|null $payment_type_id
 * @property int|null $transaction_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $type_id
 * @property int|null $owner_id // tariff, book, video table id
 * @property int|null $table_id // enroll, user_tariff, order table id
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property User $user
 * @property $course
 */
class UserPayment extends \soft\db\ActiveRecord
{
    use PaymentTypeTrait;
    use TariffBookSpecialCourseTrait;

    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'payment_type_id', 'type_id'], 'required'],
            [['amount'], 'integer', 'min' => '0'],
            [['user_id', 'amount', 'payment_type_id', 'transaction_id', 'created_by',
                'updated_by', 'created_at', 'updated_at', 'type_id', 'owner_id', 'table_id'], 'integer'],
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
            'user_id' => 'Foydalanuvchi',
            'amount' => "Pul miqdori",
            'payment_type_id' => 'To\'lov turi',
            'transaction_id' => 'Transaction ID',
            'type_id' => 'Sababi',
            'owner_id' => 'Owner ID',
            'table_id' => 'Table ID',
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

    /**
     * @return ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Video::className(), ['id' => 'type_id']);
    }

    //</editor-fold>

    /**
     * @return array
     */
    public static function map()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }
}
