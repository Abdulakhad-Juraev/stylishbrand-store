<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 26-Apr-24, 10:09
 */

namespace common\modules\uzum\traits;

use soft\helpers\ArrayHelper;

trait UzumStatusTrait
{
    /**
     * @var string
     */
    public static $status_confirmed = 'CONFIRMED';

    /**
     * @var string
     */
    public static $status_reversed = 'REVERSED';

    /**
     * @var string
     */
    public static $status_failed = 'FAILED';

    /**
     * @var string
     */
    public static $status_created = 'CREATED';


    /**
     * @return array
     */
    public static function statuses()
    {

        return [
            self::$status_confirmed,
            self::$status_reversed,
            self::$status_failed,
            self::$status_created,
        ];
    }

    /**
     * @return mixed|null
     */
    public function getStatusName()
    {
        return ArrayHelper::getArrayValue(self::statuses(), $this->status);
    }
}