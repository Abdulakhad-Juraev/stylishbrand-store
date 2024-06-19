<?php

namespace common\modules\video\traits;

use common\modules\video\models\Video;
use soft\helpers\ArrayHelper;
use soft\helpers\Html;

trait VideoPriceTypeTrait
{

    /**
     * @return string[]
     */
    public static function priceTypes()
    {
        return [
            Video::PRICE_TYPE_FREE => 'Bepul',
            Video::PRICE_TYPE_PREMIUM => 'Obuna',
        ];
    }

    /**
     * @return int[]|string[]
     */
    public static function priceTypeKeys()
    {
        return array_keys(self::priceTypes());
    }

    /**
     * @return string|int
     */
    public function getPriceTypeName()
    {
        return ArrayHelper::getArrayValue(self::priceTypes(), $this->price_type_id, $this->price_type_id);
    }

    /**
     * @return bool
     */
    public function getIsFree()
    {
        if ($this->getIsPartial()) {
            return $this->getParent()->cache()->one()->getIsFree();
        }
        return $this->price_type_id == Video::PRICE_TYPE_FREE;
    }

    /**
     * @return bool
     */
    public function getIsPremium()
    {
        if ($this->getIsPartial()) {
            return $this->getParent()->cache()->one()->getIsPremium();
        }
        return $this->price_type_id == Video::PRICE_TYPE_PREMIUM;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getPriceTypeLabel()
    {
        if ($this->getIsFree()){
            return Html::badge('Bepul', 'success');
        }
        if ($this->getIsPremium()){
            return Html::badge('Obuna', 'primary');
        }
        return $this->price_type_id;
    }

}
