<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "paycom_transaction".
 *
 * @property int $id
 * @property string|null $paycom_transaction_id
 * @property string|null $paycom_time
 * @property int|null $paycom_time_datetime
 * @property int|null $create_time
 * @property int|null $perform_time
 * @property int|null $cancel_time
 * @property int|null $amount
 * @property int|null $state
 * @property int|null $reason
 * @property string|null $receivers
 * @property int|null $order_id
 * @property int|null $time
 */
class PaymeTransaction extends \soft\db\ActiveRecord
{
    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        return 'paycom_transactions';
    }

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
        return [
            [['paycom_time_datetime', 'create_time', 'perform_time', 'cancel_time', 'amount', 'state', 'reason', 'order_id', 'time'], 'integer'],
            [['paycom_transaction_id', 'paycom_time', 'receivers'], 'string', 'max' => 255],
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
            'paycom_transaction_id' => 'Paycom Transaction ID',
            'paycom_time' => 'Paycom Time',
            'paycom_time_datetime' => 'Paycom Time Datetime',
            'create_time' => 'Create Time',
            'perform_time' => 'Perform Time',
            'cancel_time' => 'Cancel Time',
            'amount' => 'Amount',
            'state' => 'State',
            'reason' => 'Reason',
            'receivers' => 'Receivers',
            'order_id' => 'Order ID',
            'time' => 'Time',
        ];
    }
    //</editor-fold>

}
