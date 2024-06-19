<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 26-Mar-24, 16:17
 */

namespace common\traits;

use common\modules\order\models\Order;
use common\modules\tariff\models\Tariff;
use common\modules\userBalance\models\UserTariff;
use common\modules\userBalance\query\UserTariffQuery;
use yii\db\ActiveQuery;

/**
 *
 * User modeli uchun tariflar bilan bog'liq methodlar
 * @see \common\models\User
 *
 * @property UserTariff[] $userTariffs
 * @property UserTariff $activeUserTariff
 * @property UserTariff $lastActiveUserTariff
 * @property UserTariff $lastActiveProUserTariff
 * @property UserTariff $lastActiveVideoPodcastUserTariff
 * @property UserTariff $lastActiveVideoUserTariff
 * @property UserTariff $lastActivePodcastUserTariff
 * @property UserTariff $lastUserTariff
 * @property Tariff $activeTariff
 * @property int $userTariffsSum
 * @property bool $hasActiveTariff
 **/
trait UserTariffTrait
{
    /**
     * @var int User sotib olgan barcha tariflarining um. summasi
     * @see getUserTariffsSum
     */
    private $_userTariffsSum;

    /**
     * @var
     */
    private $_hasActiveTariff;


    /**
     * @return UserTariffQuery|ActiveQuery
     */
    public function getUserTariffs()
    {
        return $this->hasMany(UserTariff::class, ['user_id' => 'id']);
    }

    /**
     * @return UserTariffQuery
     */
    public function getActiveUserTariff()
    {
        return $this->hasOne(UserTariff::class, ['user_id' => 'id'])->nonExpired();
    }

    /**
     * @return UserTariffQuery
     */
    public function getLastActiveUserTariff()
    {
        return $this->hasOne(UserTariff::class, ['user_id' => 'id'])->nonExpired()->orderBy(['expired_at' => SORT_DESC]);
    }

    /**
     * @return UserTariffQuery|ActiveQuery
     */
    public function getLastUserTariff()
    {
        return $this->hasOne(UserTariff::class, ['user_id' => 'id'])
            ->orderBy(['expired_at' => SORT_DESC]);
    }


    /**
     * @return mixed
     */
    public function getActiveTariff()
    {
        return $this->hasOne(Tariff::class, ['id' => 'tariff_id'])->via('activeUserTariff');
    }

    /**
     * Foydalanuvchi ayni vaqtda faol tarifga egami?
     * Agar true bo'lsa, foydalanuvchi premium videolarni ko'rishi mn bo'ladi.
     * @return bool
     */
    public function getHasActiveTariff()
    {
        if ($this->_hasActiveTariff === null) {
            $this->_hasActiveTariff = $this->getActiveUserTariff()->exists();
        }
        return $this->_hasActiveTariff;
    }


    /**
     * Purchase new tariff
     * @param Tariff $tariff
     * @return bool
     */
    public function purchaseTariff(Tariff $tariff, $order_id = null, $payment_type = null)
    {

        $lastTariff = $this->lastUserTariff;

        if ($lastTariff) {
            $begin = $lastTariff->expired_at;
            if ($begin < today()) {
                $begin = today();
            }
        } else {
            $begin = today();
        }

        $userTariff = new UserTariff([
            'tariff_id' => $tariff->id,
            'user_id' => $this->id,
            'price' => $tariff->price,
            'started_at' => $begin,
            'expired_at' => strtotime("+" . $tariff->getDuration(), $begin),
            'order_id' => $order_id,
            'payment_type_id' => $payment_type
        ]);

        return $userTariff->save();
    }

    /**
     * User sotib olgan barcha tariflarining um. summasi
     * @return int
     */
    public function getUserTariffsSum()
    {
        if ($this->_userTariffsSum === null) {
            $this->_userTariffsSum = (int)$this->getUserTariffs()->sum('price');
        }
        return $this->_userTariffsSum;
    }
}