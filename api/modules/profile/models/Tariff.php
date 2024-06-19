<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 23-Mar-24, 14:56
 */

namespace api\modules\profile\models;

class Tariff extends \common\modules\tariff\models\Tariff
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
            'price',
            'priceFormat' => function (Tariff $model) {
                return as_sum($model->price);
            },
            'old_price',
            'OldPriceFormat' => function (Tariff $model) {
                return as_sum($model->old_price);
            },
            'durationName',
            'is_recommend',
            'calcTariffDiscount'
        ];

    }
}