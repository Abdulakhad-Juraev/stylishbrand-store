<?php

namespace soft\db;

use soft\helpers\ArrayHelper;
use soft\helpers\Html;

trait ActiveRecordStatusTrait
{

    public $statusAttribute = 'status';
    public static $statuses;
    public static $statuseTypes;

    public static function activeStatuses()
    {
        return [static::STATUS_INACTIVE, self::STATUS_ACTIVE];
    }

    /**
     * @return array active statuslarni array ko'rinishda ajratib olish
     * [
     *      0 => 'Nofaol',
     *      1 => 'Faol',
     * ]
     */
    public static function statuses()
    {
        if (static::$statuses === null) {

            $defaultStatuses = static::defaultStatuses();
            $customStatuses = static::customStatuses();
            $result = [];

            foreach (static::activeStatuses() as $status) {

                if (isset($customStatuses[$status])) {
                    $result[$status] = $customStatuses[$status];
                } else if (isset($defaultStatuses[$status])) {
                    $result[$status] = $defaultStatuses[$status];
                }
            }

            static::$statuses = $result;
        }

        return static::$statuses;
    }

    /**
     * Ushbu method child class tomonidan override qilish uchun mo'ljallangan
     * Qaysidir statusni labelini o'zgartirish yoki yangi qo'shishda ishlaydi
     * @return array
     */
    public static function customStatuses()
    {
        return [];
    }

    public static function defaultStatuses()
    {
        return [
            static::STATUS_DELETED => 'Ўчирилган',
            static::STATUS_INACTIVE => 'Нофаол',
            static::STATUS_ACTIVE => 'Фаол',
            static::STATUS_CANCELED => 'Бекор қилинди',
            static::STATUS_NEW => 'Янги',
            static::STATUS_WAITING => 'Кутиш реж.',
            static::STATUS_ACCEPTED => 'Қабул қилинди',
            static::STATUS_ALLOWED => 'Рухсат берилди',
            static::STATUS_VIEWED => "Ko'rildi",
            static::STATUS_IN_PROCESS => "Jarayonda",
            static::STATUS_FIXED => "To'g'rilandi",
        ];
    }

    /**
     * Status holatini ko'rsatish
     * @return mixed
     * @throws \Exception
     */
    public function getStatusLabel()
    {
        if ($this->hasAttribute($this->statusAttribute)) {
            return ArrayHelper::getValue(self::statuses(), $this->{$this->statusAttribute}, $this->{$this->statusAttribute});
        }
        return '';
    }

    /**
     * @return array active statuslarni array ko'rinishda ajratib olish
     * [
     *      0 => 'secondary',
     *      1 => 'primary',
     * ]
     */
    public static function statuseTypes()
    {

        if (static::$statuseTypes === null) {

            $defaultStatuseTypes = static::defaultStatuseTypes();
            $customStatuseTypes = static::customStatuseTypes();
            $result = [];

            foreach (static::activeStatuses() as $status) {

                if (isset($customStatuseTypes[$status])) {
                    $result[$status] = $customStatuseTypes[$status];
                } else if (isset($defaultStatuseTypes[$status])) {
                    $result[$status] = $defaultStatuseTypes[$status];
                }
            }

            static::$statuseTypes = $result;
        }

        return static::$statuseTypes;
    }

    /**
     * Ushbu method child class tomonidan override qilish uchun mo'ljallangan
     * Qaysidir statusni typeni o'zgartirish yoki yangi qo'shishda ishlaydi
     * @return array
     */
    public static function customStatuseTypes()
    {
        return [];
    }

    /**
     * @return string[]
     */
    public static function defaultStatuseTypes()
    {
        return [
            static::STATUS_DELETED => 'secondary',
            static::STATUS_INACTIVE => 'danger',
            static::STATUS_ACTIVE => 'success',
            static::STATUS_CANCELED => 'warning',
            static::STATUS_NEW => 'danger',
            static::STATUS_WAITING => 'warning',
            static::STATUS_ACCEPTED => 'success',
            static::STATUS_ALLOWED => 'success',
            static::STATUS_IN_PROCESS => 'warning',
            static::STATUS_FIXED => 'success',
        ];
    }

    /**
     * Status holatini ko'rsatish
     * @return mixed
     */
    public function getStatusType()
    {
        if ($this->hasAttribute($this->statusAttribute)) {
            return ArrayHelper::getValue(self::statuseTypes(), $this->{$this->statusAttribute}, 'primary');
        }
        return '';
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getStatusBadge()
    {
        return Html::badge($this->getStatusLabel(), $this->getStatusType());
    }

    /**
     * Ushbu obyektdan status attributini qiymatini olib beradi
     * Agar status attributi bo'lmasa false qaytaradi
     * @return mixed
     */
    public function getStatusAttributeValue()
    {
        return ArrayHelper::getValue($this, $this->statusAttribute, false);
    }

    /**
     * @return bool
     */
    public function getIsNew()
    {
        return $this->getStatusAttributeValue() == self::STATUS_NEW;
    }

    /**
     * @return bool
     */
    public function getIsWaiting()
    {
        return $this->getStatusAttributeValue() == self::STATUS_WAITING;
    }

    /**
     * @return bool
     */
    public function getIsAllowed()
    {
        return $this->getStatusAttributeValue() == self::STATUS_ALLOWED;
    }

    /**
     * @return bool
     */
    public function getIsCanceled()
    {
        return $this->getStatusAttributeValue() == self::STATUS_CANCELED;
    }

}
