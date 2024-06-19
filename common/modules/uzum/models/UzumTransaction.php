<?php

namespace common\modules\uzum\models;

use common\models\User;
use common\modules\order\models\Order;
use common\modules\uzum\traits\UzumStatusTrait;
use soft\db\ActiveQuery;
use Yii;

/**
 * This is the model class for table "uzum_transaction".
 *
 * @property int $id
 * @property int|null $order_id
 * @property int|null $amount
 * @property string|null $timestamp
 * @property int|null $serviceId
 * @property string|null $transId
 * @property string|null $status
 * @property string|null $transTime
 * @property string|null $confirmTime
 * @property string|null $reverseTime
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property Order $order
 */
class UzumTransaction extends \soft\db\ActiveRecord
{
    use UzumStatusTrait;

    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'uzum_transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'amount', 'serviceId', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['timestamp', 'transId', 'status', 'transTime', 'confirmTime', 'reverseTime'], 'safe'],
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function labels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'amount' => 'Amount',
            'timestamp' => 'Timestamp',
            'serviceId' => 'Service ID',
            'transId' => 'Trans ID',
            'status' => 'Status',
            'transTime' => 'Trans Time',
            'confirmTime' => 'Confirm Time',
            'reverseTime' => 'Reverse Time',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
    //</editor-fold>
}
