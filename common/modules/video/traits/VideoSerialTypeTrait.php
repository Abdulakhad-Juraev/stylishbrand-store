<?php

namespace common\modules\video\traits;

use common\modules\video\models\Video;
use soft\helpers\ArrayHelper;

/**
 * @property-read string $serialTypeName
 */
trait VideoSerialTypeTrait
{

    /**
     * @return string[]
     */
    public static function serialTypes()
    {
        return [
            Video::SERIAL_TYPE_SINGLE => 'Video',
            Video::SERIAL_TYPE_SERIAL => 'Kurs',
        ];
    }

    /**
     * @return mixed|null
     */
    public function getSerialTypeName()
    {
        return ArrayHelper::getArrayValue(self::serialTypes(), $this->serial_type_id, $this->serial_type_id);
    }

    /**
     * @return bool
     */
    public function getIsSerial(): bool
    {
        return $this->serial_type_id == Video::SERIAL_TYPE_SERIAL;
    }

    /**
     * @return bool
     */
    public function getIsSingle(): bool
    {
        return $this->serial_type_id == Video::SERIAL_TYPE_SINGLE;

    }

    /**
     * @return bool
     */
    public function getIsPartial(): bool
    {
        return $this->serial_type_id == Video::SERIAL_TYPE_PART;

    }

}
