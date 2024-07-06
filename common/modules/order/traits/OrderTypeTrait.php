<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 26-Mar-24, 12:05
 */

namespace common\modules\order\traits;

trait OrderTypeTrait
{

    /**
     * Naqd pul orqali
     */
    public static $rejected = 0;

    /**
     * Kartadan kartaga
     */
    public static $waited = 1;

    /**
     * Click orqali to'lov
     */
    public static $done = 2;

    /**
     * Payme orqali to'lov
     */
    /**
     * @return string[]
     */
    public static function orderTypes()
    {
        return [
            self::$rejected => 'Rad Etildi',
            self::$waited => 'Kutilyapti',
            self::$done => 'Bajarildi',
        ];
    }
    /**
     * @return int|string|null
     */
    public function getOrderTypeName()
    {
        return self::orderTypes()[$this->order_type] ?? $this->order_type;
    }

    /**
     * @return int[]|string[]
     */
    public static function typeOrderKeys()
    {
        return array_keys(self::orderTypes());
    }
}