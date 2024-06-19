<?php

namespace soft\i18n;

use soft\helpers\PhoneHelper;
use yii\i18n\Formatter as YiiFormatter;
use soft\helpers\Html;
use Yii;

class Formatter extends YiiFormatter
{

    public $nullDisplay = '';

    public $dateFormat = 'php:d.m.Y';
    public $datetimeFormat = 'php:d.m.Y H:i';

    public $thousandSeparator = ' ';

    //<editor-fold desc="Currency" defaultstate="collapsed">

    /**
     * @param $value mixed
     * @return string
     */
    public function asDollar($value)
    {
        if ($value === null) {
            return $this->nullDisplay;
        }
        return $this->asDecimal($value) . " $";
    }

    /**
     * @param $value mixed
     * @return string
     */
    public function asSum($value)
    {
        if ($value === null) {
            return "";
        }
        return $this->asInteger($value) . " " . Yii::t('site', "sum");
    }

    //</editor-fold>

    //<editor-fold desc="Image" defaultstate="collapsed">

    /**
     * @param $value string
     * @param $width string
     * @return string|null
     */
    public function asTinyImage($value, $width = '80px')
    {

        if ($value === null) {
            return $this->nullDisplay;
        }
        $options['width'] = $width;
        return $this->asImage($value, $options);
    }

    /**
     * @param $value string
     * @param $width string
     * @return string|null
     */
    public function asLittleImage($value, $width = '150px')
    {

        if ($value === null) {
            return $this->nullDisplay;
        }
        $options['width'] = $width;
        return $this->asImage($value, $options);

    }

    /**
     * @param $value string
     * @param $width string
     * @param array $options
     * @return string|null
     */
    public function asMiddleImage($value, $width = '300px', $options = [])
    {

        if ($value === null) {
            return $this->nullDisplay;
        }
        $options['width'] = $width;
        return $this->asImage($value, $options);
    }

    /**
     * @param $value string
     * @param $size string
     * @param $options array
     * @return string
     * @throws \Exception
     */
    public function asThumbnail($value = null, $size = '75px', $options = [])
    {

        if ($value === null) {
            return $this->nullDisplay;
        }

        $class = isBs4() ? "img-thumbnail" : 'thumbnail';

        Html::addCssClass($options, $class);
        Html::addCssStyle($options, "max-height:{$size};max-width:{$size};display: block;margin-left: auto;margin-right: auto;");
        return Html::img($value, $options);

    }

    //</editor-fold>

    //<editor-fold desc="Additional" defaultstate="collapsed">

    /**
     * @param $value bool
     * @param $text1 string
     * @param $text2 string
     * @return string
     * @throws \Exception
     */
    public function asBool($value, $text1 = null, $text2 = null)
    {
        if ($text1 == null) {
            $text1 = t('Yes', 'yii');
        }
        if ($text2 == null) {
            $text2 = t('No', 'yii');
        }
        if ($value) {
            return Html::badge($text1, 'success');
        } else {
            return Html::badge($text2, 'danger');
        }
    }

    /**
     * @param $value string
     * @param $length int
     * @param $end string
     * @return string
     */
    public function asShortText($value, $length = 150, $end = " ...")
    {
        $text = strip_tags($value);
        if (strlen($text) < $length) {
            return $text;
        }
        return mb_substr(strip_tags($text), 0, $length) . $end;
    }

    /**
     * @param $value int
     * @return string
     */
    public function asFileSize($value = null)
    {
        if ($value === null) {
            return $this->nullDisplay;
        }
        $size = intval($value);
        if ($size <= 0) {
            return '';
        }

        if ($size < 1024) {
            return $this->asDecimal($size, 2) . " Bayt";
        }
        if ($size < 1024 * 1024) {
            return $this->asDecimal($size / 1024, 2) . " KB";
        }
        return $this->asDecimal($size / 1024 / 1024, 2) . " MB";

    }

    //</editor-fold>

    //<editor-fold desc="Date and time" defaultstate="collapsed">

    /**
     * @param $value int timestapm
     * @return string|null
     */
    public function asDateUz($value = null)
    {
        if ($value === null) {
            return $this->nullDisplay;
        }
        $month = Yii::t('site', date('M', $value));

        return date('d', $value) . "-" . $month . "-" . date('Y', $value);
    }

    /**
     * @param $value integer
     * @return string|null
     */
    public function asFullDateUz($value = null)
    {
        if ($value === null) {
            return $this->nullDisplay;
        }
        $month = $this->fullMonthName(date('F', $value));
        return date('d', $value) . "-" . $month . "-" . date('Y', $value);
    }

    /**
     * @param $value integer
     * @return false|string|null
     */
    public function asTimeUz($value = null)
    {
        if ($value === null) {
            return $this->nullDisplay;
        }

        return date('H:i', $value);
    }

    /**
     * @param $value integer
     * @return string|null Formatted datetime
     */
    public function asDateTimeUz($value = null)
    {
        if ($value === null) {
            return $this->nullDisplay;
        }

        return $this->asDateUz($value) . " " . $this->asTimeUz($value);
    }

    /**
     * @param $value integer
     * @return string|null
     */
    public function asFullDateTimeUz($value = null)
    {
        if ($value === null) {
            return $this->nullDisplay;
        }

        return $this->asFullDateUz($value) . " " . $this->asTimeUz($value);
    }


    public function fullMonthName($monthNumber = 0)
    {
        switch ($monthNumber) {

            case '01' :
                return Yii::t('site', 'January');
            case '02' :
                return Yii::t('site', 'February');
            case '03' :
                return Yii::t('site', 'March');
            case '04' :
                return Yii::t('site', 'April');
            case '05' :
                return Yii::t('site', 'May');
            case '06' :
                return Yii::t('site', 'June');
            case '07' :
                return Yii::t('site', 'July');
            case '08' :
                return Yii::t('site', 'August');
            case '09' :
                return Yii::t('site', 'September');
            case '10' :
                return Yii::t('site', 'October');
            case '11' :
                return Yii::t('site', 'November');
            case '12' :
                return Yii::t('site', 'December');
            default:
                return false;
        }
    }

    /**
     * @param $value integer
     * @return int|string
     */
    public function asGmtime($value = null)
    {
        if ($value == null) {
            return 0;
        }
        $value = intval($value);

        $hours = floor($value / 3600);
        $minutes = floor((int)($value / 60) % 60);
        $seconds = $value % 60;

        $minutesText = strval($minutes);
        $minutesText = $minutes < 10 ? '0' . $minutesText : $minutesText;

        $secondsText = strval($seconds);
        $secondsText = $seconds < 10 ? '0' . $secondsText : $secondsText;


        if ($hours > 0) {

            $hoursText = strval($hours);
            $hoursText = $hours < 10 ? '0' . $hoursText : $hoursText;

            return "$hoursText:$minutesText:$secondsText";
        }

        return "$minutesText:$secondsText";
    }

    //</editor-fold>

    //<editor-fold desc="Phone" defaultstate="collapsed">


    public function asClearedPhoneNumber($value)
    {
        if ($value === null) {
            return $this->nullDisplay;
        }
        return PhoneHelper::clearPhoneNumber($value);
    }

    /**
     * @param string|null $value phone number, like "+998911234567", or "911234567", or "998911234567"
     * @return string phone number with operator code, like "911234567"
     */
    public function asShortPhoneNumber($value)
    {
        if ($value === null) {
            return $this->nullDisplay;
        }
        return PhoneHelper::removeCountryCode($value);
    }

    /**
     * @param $value
     * @return array|string|string[]
     */
    public function asFormattedShortPhoneNumber($value)
    {
        if ($value === null) {
            return $this->nullDisplay;
        }
        return PhoneHelper::formatPhoneNumber($value);
    }

    //</editor-fold>

}

?>
