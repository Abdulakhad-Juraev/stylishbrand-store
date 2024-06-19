<?php

namespace common\modules\video\traits;

use api\models\User;
use Yii;
use yii\db\ActiveRecord;

/**
 *
 *
 * @property bool $canWatch
 */
trait VideoAccessTrait
{

    /**
     * User ushbu videoni ko'rishga ruxsati bormi?
     * @return bool
     */
    public function getCanWatch(): bool
    {

        $user = Yii::$app->user;

        if ($user->isGuest && $this->getIsFree()) {
            return true;
        }

        if ($user->isGuest && !$this->getIsFree()) {
            return false;
        }

        if ($this->getIsFree()) {
            return true;
        }


        return Yii::$app->user->identity->hasActiveTariff;

    }


}
