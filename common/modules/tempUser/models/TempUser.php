<?php

namespace common\modules\tempUser\models;

use common\modules\tempUser\query\TempUserQuery;
use Yii;

/**
 * This is the model class for table "temp_user".
 *
 * @property int $id
 * @property int|null $code
 * @property int|null $expire_at
 * @property int|null $is_verified
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string $phone [varchar(20)]
 * @property bool $is_registered [tinyint(1)]
 *
 *
 * @property-read bool $isExpired
 *
 */
class TempUser extends \soft\db\ActiveRecord
{

    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone', 'code', 'expire_at', 'is_verified', 'is_registered'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'yii\behaviors\TimestampBehavior',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function labels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'expire_at' => 'Expire At',
            'is_verified' => 'Is Verified',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function find()
    {
        return new TempUserQuery(get_called_class());
    }

    //</editor-fold>

    /**
     * @return bool
     */
    public function getIsExpired(): bool
    {
        return time() > $this->expire_at;
    }

    /**
     * @return bool
     */
    public function verify(): bool
    {
        $this->is_verified = true;
        return $this->save(false);
    }

    /**
     * Register yoki Resetda tel. raqamni kiritib, sms junatilganidan keyin
     * agar user qayta sms jo'natishni bossa, qayta sms jo'natishga ehtiyoj bormi?
     * Masalan, user 1 daqiqa (60 sekund) dan keyingina sms jo'natishi mn.
     * @param int $verificationDuration sekund - kodni yaroqlilik muddati
     * @param int $resendCodeAfter sekund - qayta smsni necha sekunndan keyin jo'natishi mn.
     * @return bool
     */
    public function needResendCode(int $verificationDuration, int $resendCodeAfter)
    {
        if ($this->is_registered || $this->is_verified) {
            return true;
        }

        if ($this->isExpired) {
            return true;
        }

        /** @var int kodni generatsiya qilingan vaqti */
        $generatedTime = $this->expire_at - $verificationDuration;

        /** @var int kodni qayta jo'natish mn bo'lgan vaqt */
        $resendTime = $generatedTime + $resendCodeAfter;

        // agar hozirgi vaqt kodni qayta jo'natish mn bo'lgan vaqtdan katta bo'lsa, demak
        // kodni qayta jonatish mn
        return time() > $resendTime;
    }

}
