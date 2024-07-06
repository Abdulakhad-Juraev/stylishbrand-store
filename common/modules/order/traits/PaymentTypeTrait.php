<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 26-Mar-24, 12:05
 */

namespace common\modules\order\traits;

trait PaymentTypeTrait
{

    /**
     * Naqd pul orqali
     */
    public static $type_cash = 0;

    /**
     * Kartadan kartaga
     */
    public static $type_card = 1;

    /**
     * Click orqali to'lov
     */
    public static $type_click = 2;

    /**
     * Payme orqali to'lov
     */
    public static $type_payme = 3;

    /**
     * Admin orqali to'lov
     */
    public static $type_admin = 4;

    /**
     * Uzum orqali to'lov
     */
    public static $type_uzum = 5;


    /**
     * @return string[]
     */
    public static function types()
    {
        return [
            self::$type_cash => 'Naqd pul',
            self::$type_card => 'Kartadan kartaga',
            self::$type_click => 'Click',
            self::$type_payme => 'Payme',
            self::$type_uzum => 'Uzum',
//            self::$type_admin => 'Admin orqali',
        ];
    }

    /**
     * @return int|string|null
     */
    public function getTypeName()
    {
        return self::types()[$this->payment_type] ?? $this->payment_type;
    }

    /**
     * @return int[]|string[]
     */
    public static function typeKeys()
    {
        return array_keys(self::types());
    }
}