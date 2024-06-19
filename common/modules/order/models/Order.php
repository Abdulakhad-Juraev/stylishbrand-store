<?php

namespace common\modules\order\models;

use common\models\User;
use common\modules\book\models\Book;
use common\modules\order\traits\PaymentTypeTrait;
use common\modules\tariff\models\Tariff;
use common\modules\video\models\Video;
use common\traits\TariffBookSpecialCourseTrait;
use soft\db\ActiveRecord;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $price
 * @property int|null $type_id tariff/special-course/book
 * @property int|null $video_id
 * @property int|null $tariff_id
 * @property int|null $book_id
 * @property int|null $payment_type_id To'lov turi
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $transaction_id
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property User $user
 * @property Video $video
 * @property Tariff $tariff
 * @property Book $book
 */
class Order extends ActiveRecord
{
    use PaymentTypeTrait;
    use TariffBookSpecialCourseTrait;

    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'price', 'type_id', 'video_id', 'tariff_id', 'book_id', 'payment_type_id',
                'status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'transaction_id'
            ], 'integer'],
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
            'user_id' => 'Foydalanuvchi (ID)',
            'price' => 'Sotib olgan narxi',
            'type_id' => 'Turi',
            'video_id' => 'Video',
            'tariff_id' => 'Obuna',
            'book_id' => 'Kitob',
            'payment_type_id' => "To'lov turi",
//            'status' => 'Status',
//            'created_by' => 'Created By',
//            'updated_by' => 'Updated By',
//            'created_at' => 'Created At',
//            'updated_at' => 'Updated At',
        ];
    }
    //</editor-fold>

    //<editor-fold desc="Relations" defaultstate="collapsed">

    /**
     * @return ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getVideo()
    {
        return $this->hasOne(Video::className(), ['id' => 'video_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTariff()
    {
        return $this->hasOne(Tariff::className(), ['id' => 'tariff_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Book::className(), ['id' => 'book_id']);
    }

    //</editor-fold>

    /**
     * @return bool to'lov qilindimi yoki yo'q
     * @todo to'lov masalasini o'ylab ko'rish kerak
     */
    public function getIsPayed()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    /**
     * @return int|float to'lanishi kerak bo'lgan summa
     * @todo to'lanishi kerak bo'lgan summani hal qilish kerak
     */
    public function getMustPayedSum()
    {
        return (int)$this->price;
    }

    /**
     * @param $amount int|float Amount of payed money
     * @param $payedBy
     * @param $transaction_id
     * @return bool
     */
    public function pay($amount, $payedBy = null, $transaction_id = null)
    {

        $mustPaymentSum = (int)$this->getMustPayedSum();
        $amount = (int)$amount;

        if ($mustPaymentSum < $amount) {
            return false;
        }
        $this->status = self::STATUS_ACTIVE;
        $this->transaction_id = $transaction_id;

        $user = User::findOne($this->user_id);

        if ($this->type_id == Order::$type_id_tariff) {
            $tariff = Tariff::findOne($this->tariff_id);
            $user->purchaseTariff($tariff, $this->id, $this->payment_type_id);
        }

        // kitob uchun tekshirish shart emas, order tabledan olinaveradi ma'lumotlar

        // maxsus kursni sotib olish
        if ($this->type_id == Order::$type_id_special_course) {
            $course = Video::findOne($this->video_id);
            $user->purchaseCourse($course, $this, $this->payment_type_id);
        }

        $user->addBalance($this->price, $this->payment_type_id, $this->type_id, $this->transaction_id);
        return $this->save(false);
    }
}
