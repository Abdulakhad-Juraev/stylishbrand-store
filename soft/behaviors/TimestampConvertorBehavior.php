<?php

namespace soft\behaviors;

use yii\base\Behavior;
use yii\base\InvalidArgumentException;
use yii\db\BaseActiveRecord;

class TimestampConvertorBehavior extends Behavior
{

    /**
     * @var string|array attribute(s) to convert to timestamp
     */
    public $attribute = 'date';

    public function init()
    {
        if (empty($this->attribute)) {
            throw new InvalidArgumentException('The "attribute" property must be set!');
        }
    }

    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'convertToTimestamp',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'convertToTimestamp',
        ];
    }

    public function convertToTimestamp()
    {
        $attributes = (array)$this->attribute;
        foreach ($attributes as $attribute) {
            $value = $this->owner->$attribute;
            if (!empty($value) && !$this->isTimestamp($value)) {
                $this->owner->$attribute = strtotime($this->owner->$attribute);
            }
        }
    }

    /**
     * @param $string mixed Value to check
     * @return bool
     */
    private function isTimestamp($string)
    {
        return (1 === preg_match('~^[1-9][0-9]*$~', $string));
    }

    /**
     * Check if given attribute is a timestamp format
     *
     * ```php
     * $model->isAttributeTimestamp('lastVisit');
     * ```
     * @param $attribute string Attribute name
     * @return bool
     */
    public function isAttributeTimestamp($attribute)
    {
        return $this->isTimestamp($this->owner->$attribute);
    }

    /**
     * Get timestamp value of given attribute
     *
     * ```php
     * $model->getAttributeTimestampValue('date');
     * ```
     * @param $attribute string
     * @return false|int|mixed
     */
    public function getAttributeTimestampValue($attribute)
    {
        return $this->isAttributeTimestamp($attribute) ? $this->owner->$attribute : strtotime($this->owner->$attribute);
    }

    /**
     * Set timestamp value of given attribute
     *
     * ```php
     * $model->attributeToTimestamp('date');
     * ```
     * @param $attribute string
     */
    public function attributeToTimestamp($attribute)
    {
        $this->owner->$attribute = $this->getAttributeTimestampValue($attribute);
    }
}
