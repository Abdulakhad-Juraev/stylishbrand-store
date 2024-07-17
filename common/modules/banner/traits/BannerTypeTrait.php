<?php

namespace common\modules\banner\traits;

trait BannerTypeTrait
{
    public static $top_banner = 1;

    public static $middle_banner = 2;
    public static $bottom_banner = 3;


    /**
     * @return string[]
     */
    public static function types()
    {
        return [
            self::$top_banner => 'Asosiy Banner',
            self::$middle_banner => 'Orta Banner',
            self::$bottom_banner => 'Pastgi Banner',
        ];
    }

    /**
     * @return int|string|null
     */
    public function getTypeName()
    {
        return self::types()[$this->type] ?? $this->type;
    }

    /**
     * @return int[]|string[]
     */
    public static function typeKeys()
    {
        return array_keys(self::types());
    }
}