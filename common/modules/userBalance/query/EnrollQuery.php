<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 12-Apr-24, 09:37
 */

namespace common\modules\userBalance\query;

use soft\db\ActiveQuery;

class EnrollQuery extends ActiveQuery
{
    /**
     * @return EnrollQuery
     */
    public function expired()
    {
        $this->andWhere(['<', 'enroll.end_at', time()]);
        return $this;
    }

    /**
     * @return EnrollQuery
     */
    public function nonExpired()
    {
        $this->andWhere(['>', 'enroll.end_at', time()]);
        return $this;
    }

    /**
     * Bepul a'zoliklarni filtrlash
     * @return $this
     */
    public function free()
    {
        $this->andWhere(['<', 'enroll.sold_price', 1]);
        return $this;
    }

    /**
     * Pullik a'zoliklarni filtrlash
     * @return $this
     */
    public function paid()
    {
        $this->andWhere(['>', 'enroll.sold_price', 0]);
        return $this;
    }


    /**
     * @return $this
     */
    public function started()
    {
        $this->andWhere(['<=', 'enroll.created_at', time()]);
        return $this;
    }
}