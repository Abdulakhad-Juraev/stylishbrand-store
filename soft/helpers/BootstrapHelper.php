<?php

namespace soft\helpers;

use Yii;

class BootstrapHelper
{

    private static $bsVersion;

    /**
     * @param mixed $bsVersion
     */
    public static function setBsVersion(int $bsVersion)
    {
        self::$bsVersion = $bsVersion;
    }

    /**
     * @return int
     */
    public static function getBsVersion()
    {
        if (self::$bsVersion === null) {
            $v = ArrayHelper::getValue(Yii::$app->params, 'bsVersion', '3');
            $ver = (string)$v;
            $ver = substr(trim($ver), 0, 1);
            self::setBsVersion((int)$ver);
        }
        return self::$bsVersion;
    }

    /**
     * @param int $ver
     * @return bool
     */
    public static function isBs(int $ver)
    {
        return self::getBsVersion() == $ver;
    }

    /**
     * @return bool
     */
    public static function isBs4()
    {
        return self::getBsVersion() == 4;
    }

    /**
     * @return bool
     */
    public static function isBs3()
    {
        return self::getBsVersion() == 3;
    }

}
