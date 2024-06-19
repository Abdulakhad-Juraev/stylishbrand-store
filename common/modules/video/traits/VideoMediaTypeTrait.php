<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 19-Apr-24, 15:07
 */

namespace common\modules\video\traits;

use soft\helpers\ArrayHelper;

trait VideoMediaTypeTrait
{
    /**
     * @var int
     */
    public static $media_type_id_video = 0;

    /**
     * @var int
     */
    public static $media_type_id_audio = 1;


    /**
     * @return string[]
     */
    public static function mediaTypes()
    {
        return [
            self::$media_type_id_video => 'Video',
            self::$media_type_id_audio => 'Potkast',
        ];
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getMediaTypeName()
    {

        return ArrayHelper::getValue(self::mediaTypes(), $this->media_type_id);
    }

    /**
     * @return bool
     */
    public function getIsVideo(): bool
    {
        return $this->media_type_id === self::$media_type_id_video;
    }

    /**
     * @return bool
     */
    public function getIsAudio(): bool
    {
        return $this->media_type_id === self::$media_type_id_audio;
    }
}