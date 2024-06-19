<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 01-Apr-24, 10:58
 */

namespace common\modules\video\traits;

trait BeforeAfterTypeTraits
{
    /**
     * Kursga a'zo bo'lishdan oldin
     * @var int
     */
    public static $type_id_berfore = 0;

    /**
     * Kursni tamomlagandan keyin
     * @var int
     */
    public static $type_id_after = 1;

    /**
     * @return string[]
     */
    public static function types()
    {
        return [
            self::$type_id_berfore => "Kursga a'zo bo'lishdan oldin",
            self::$type_id_after => "Kursni tamomlagandan keyin",
        ];
    }

    /**
     * @return int|string|null
     */
    public function getTypeName()
    {
        return self::types()[$this->before_after_type_id] ?? $this->before_after_type_id;
    }

    /**
     * @return int[]|string[]
     */
    public static function typeKeys()
    {
        return array_keys(self::types());
    }

}