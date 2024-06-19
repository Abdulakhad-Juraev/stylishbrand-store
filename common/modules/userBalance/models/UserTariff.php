<?php

namespace common\modules\userBalance\models;

use common\models\User;
use common\modules\order\models\Order;
use common\modules\order\traits\PaymentTypeTrait;
use common\modules\tariff\models\Tariff;
use common\modules\userBalance\query\UserTariffQuery;
use soft\behaviors\InvalidateCacheBehavior;
use soft\behaviors\TimestampConvertorBehavior;
use Yii;
use yii\base\InvalidConfigException;

/**
 * This is the model class for table "user_tariff".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $tariff_id
 * @property int|null $price
 * @property int|null $started_at
 * @property int|null $expired_at
 * @property int|null $order_id
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $type_id
 * @property int|null $payment_type_id
 *
 * @property User $createdBy
 * @property Order $order
 * @property Tariff $tariff
 * @property User $updatedBy
 * @property User $user
 *
 */
class UserTariff extends \soft\db\ActiveRecord
{
    const CACHE_TAGS = ['_userTariff'];

    use PaymentTypeTrait;

    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_tariff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'tariff_id', 'payment_type_id'], 'required'],
            [['user_id', 'tariff_id', 'price', 'started_at', 'expired_at', 'order_id',
                'status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'type_id'], 'integer'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['tariff_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tariff::className(), 'targetAttribute' => ['tariff_id' => 'id']],
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
            [
                'class' => InvalidateCacheBehavior::class,
                'tags' => self::CACHE_TAGS,
            ],
            [
                'class' => TimestampConvertorBehavior::class,
                'attribute' => 'started_at'   // qayta ishlanishi kerak bo'lgan model attributi
            ],
            [
                'class' => TimestampConvertorBehavior::class,
                'attribute' => 'expired_at'   // qayta ishlanishi kerak bo'lgan model attributi
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function labels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Foydalanuvchi (ID)',
            'tariff_id' => 'Obuna',
            'price' => 'Narxi',
            'started_at' => 'Boshlanish sanasi',
            'expired_at' => 'Tugash sanasi',
            'order_id' => 'Buyurtma raqami',
            'type_id' => 'Obuna turi',
            'payment_type_id' => "To'lov turi",
        ];
    }
    //</editor-fold>

    /**
     * @return UserTariffQuery
     */
    public static function find()
    {
        return new UserTariffQuery(get_called_class());
    }

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
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTariff()
    {
        return $this->hasOne(Tariff::className(), ['id' => 'tariff_id']);
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

    /**
     * @return string
     */
    public function getFormattedPrice()
    {
        return (string)as_sum($this->price);
    }

    /**
     * @return string
     */
    public function getTariffName()
    {
        return $this->tariff->name ?? '';
    }

    /**
     * @return string|null
     * @throws InvalidConfigException
     */
    public function getFormattedStartedAt()
    {
        return Yii::$app->formatter->asDate($this->started_at, 'php:d.m.Y');
    }

    /**
     * @return string|null
     * @throws InvalidConfigException
     */
    public function getFormattedExpiredAt()
    {
        return Yii::$app->formatter->asDate($this->expired_at, 'php:d.m.Y');
    }

    /**
     * @return bool
     */
    public function getIsStarted()
    {
        return $this->started_at <= time();
    }

    /**
     * @return bool
     */
    public function getIsExpired()
    {
        return $this->expired_at < time();
    }

    /**
     * @return bool
     */
    public function getIsActive()
    {
        return !$this->getIsExpired();
    }

    /**
     * @return mixed|string
     */
    public function getDuration()
    {
        return $this->tariff->durationName ?? '';
    }
}
