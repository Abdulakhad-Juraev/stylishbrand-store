<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 23-Mar-24, 14:44
 */

namespace api\modules\profile\models;

use api\modules\profile\models\TariffGroupOption;
use api\modules\profile\models\Tariff;

class TariffGroup extends \common\modules\tariff\models\TariffGroup
{
    /**
     * @return array|string[]
     */
    public function fields()
    {
        $key = self::generateFieldParam();

        if (isset(self::$fields[$key])) {
            return self::$fields[$key];
        }

        return [
            'id',
            'name',
            'description',
            'type_id',
            'is_recommend',
            'activeTariffGroupOptions',
            'activeTariffs'
        ];

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTariffGroupOptions()
    {
        return $this->hasMany(TariffGroupOption::className(), ['group_id' => 'id']);
    }

    /**
     * @return mixed
     */
    public function getActiveTariffGroupOptions()
    {
        return $this->getTariffGroupOptions()->active();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTariffs()
    {
        return $this->hasMany(Tariff::className(), ['group_id' => 'id']);
    }

    /**
     * @return mixed
     */
    public function getActiveTariffs()
    {
        return $this->getTariffs()->active();
    }
}