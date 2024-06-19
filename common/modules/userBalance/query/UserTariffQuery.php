<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 26-Mar-24, 15:30
 */

namespace common\modules\userBalance\query;

use soft\db\ActiveQuery;

class UserTariffQuery extends ActiveQuery
{
    /**
     * @return $this
     */
    public function expired()
    {
        $this->andWhere(['<', 'expired_at', time()]);
        return $this;
    }

    /**
     * @return $this
     */
    public function nonExpired()
    {
        $this->andWhere(['>=', 'expired_at', time()]);
        return $this;
    }

    /**
     * @return $this
     */
    public function started()
    {
        $this->andWhere(['<=', 'started_at', time()]);
        return $this;
    }
}