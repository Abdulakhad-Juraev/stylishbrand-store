<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 27-Mar-24, 09:56
 */

namespace common\traits;

use soft\helpers\ArrayHelper;

trait TariffBookSpecialCourseTrait
{
    /** Turi Obunas hisoblanadi
     * @var int
     */
    public static $type_id_tariff = 0;

    /**
     * Turi maxsus kurs hisoblanandi
     * @var int
     */
    public static $type_id_special_course = 1;

    /**
     * Turi kitob hisobnaladi
     * @var int
     */
    public static $type_id_book = 2;


    /**
     * @return string[]
     */
    public static function tariffCourseBooktypes()
    {
        return [
            self::$type_id_tariff => 'Obuna',
            self::$type_id_special_course => 'Maxsus kurs',
            self::$type_id_book => 'Kitob',
        ];
    }


    /**
     * @return mixed
     * @throws \Exception
     */
    public function getTariffCourseBookTypeName()
    {
        return ArrayHelper::getValue(self::tariffCourseBooktypes(), $this->type_id);
    }


}