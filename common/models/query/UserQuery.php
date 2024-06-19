<?php

namespace common\models\query;

use common\models\User;
use common\modules\film\models\query\FilmQuery;
use yii\db\ExpressionInterface;

class UserQuery extends \soft\db\ActiveQuery
{

    /**
     * @param $isActive bool
     * @param $attribute string
     * @return $this
     */
    public function active(bool $isActive = true, $attribute = null)
    {
        $status = $isActive ? User::STATUS_ACTIVE : User::STATUS_INACTIVE;
        if ($attribute === null) {
            $attribute = 'user.status';
        }
        $this->andWhere([$attribute => $status]);
        return $this;
    }

    public function withDevicesCount()
    {
        $attribute = "(SELECT COUNT(*) from user_device WHERE user_device.user_id = user.id) as devicesCount";
        $this->safeAddSelect($attribute);
        return $this;
    }


    public function withActiveDevicesCount()
    {
        $attribute = "(SELECT COUNT(*) from user_device WHERE user_device.user_id = user.id AND user_device.status=1) as activeDevicesCount";
        $this->safeAddSelect($attribute);
        return $this;
    }

    /**
     * @return $this
     */
    public function userPaymentSum()
    {
        $attribute = "(SELECT sum(amount) from user_payment WHERE user_payment.user_id = user.id) as paymentSum";
        $this->safeAddSelect($attribute);
        return $this;
    }

    /**
     * @return $this
     */
    public function userTariffSum()
    {
        $attribute = "(SELECT sum(price) from user_tariff WHERE user_tariff.user_id = user.id) as userTariffSum";
        $this->safeAddSelect($attribute);
        return $this;
    }

    /**
     * Safely adds column(s) to select.
     * @param string|array|ExpressionInterface $columns the columns to add to the select. See [[select()]] for more
     * details about the format of this parameter.
     * @return $this
     */
    public function safeAddSelect($columns)
    {
        if ($this->select === null) {
            $this->select('user.*');
        }
        $this->addSelect($columns);
        return $this;
    }

}
