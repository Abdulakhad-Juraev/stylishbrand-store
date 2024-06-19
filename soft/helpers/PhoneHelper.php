<?php

namespace soft\helpers;

class PhoneHelper
{

    /**
     * Remove everything but numbers
     * @param $phoneNumber mixed
     * @return array|string|string[]|null
     */
    public static function clearPhoneNumber($phoneNumber)
    {
        $phoneNumber = (string)$phoneNumber;
        return preg_replace('/[^0-9]/', '', $phoneNumber);
    }

    /**
     * Telefon raqamdan davlat kodi (+998) ni olib tashlaydi
     * @param mixed $phoneNumber
     * @return false|string|null
     */
    public static function removeCountryCode($phoneNumber)
    {
        if (empty($phoneNumber)) {
            return null;
        }

        $phoneNumber = self::clearPhoneNumber($phoneNumber);

        $length = strlen($phoneNumber);
        if ($length > 9) {
            $start = $length - 9;
            $phoneNumber = substr($phoneNumber, $start);
        }

        return $phoneNumber;
    }


    /**
     * @param $value
     * @return array|string|string[]
     */
    public static function formatPhoneNumber($value)
    {

        $value = static::removeCountryCode($value);

        if (strlen($value) == 9) {
            $value = substr_replace($value, ' ', 2, 0);
            $value = substr_replace($value, '-', 6, 0);
            $value = substr_replace($value, '-', 9, 0);
        }

        return $value;
    }

    /**
     * Tel. raqamni tozalaydi va davlat kodi 998 ni qo'shib qaytaradi
     * @param $phone
     * @param bool $addPlus
     * @return string
     */
    public static function fullPhoneNumber($phone, $addPlus = false)
    {

        $phone = '998' . self::removeCountryCode($phone);
        if ($addPlus) {
            $phone = '+' . $phone;
        }
        return $phone;

    }

}
