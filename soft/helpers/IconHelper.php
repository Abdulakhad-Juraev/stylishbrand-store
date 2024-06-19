<?php

namespace soft\helpers;

use Yii;

class IconHelper
{

    private static $faVersion;

    /**
     * @param mixed $faVersion
     */
    public static function setFaVersion(int $faVersion)
    {
        self::$faVersion = $faVersion;
    }

    /**
     * @return int
     */
    public static function getFaVersion()
    {
        if (self::$faVersion === null) {
            $v = ArrayHelper::getValue(Yii::$app->params, 'faVersion', '4');
            $ver = (string)$v;
            $ver = substr(trim($ver), 0, 1);
            self::setFaVersion((int)$ver);
        }
        return self::$faVersion;
    }

    /**
     * @param int $ver
     * @return bool
     */
    public static function isFa(int $ver)
    {
        return self::getFaVersion() == $ver;
    }

    /**
     * @return bool
     */
    public static function isFa4()
    {
        return self::getFaVersion() == 4;
    }

    /**
     * @return bool
     */
    public static function isFa3()
    {
        return self::getFaVersion() == 3;
    }

    public static function checkIcon()
    {
        return '<i class="fas fa-check"></i>';
    }


}
