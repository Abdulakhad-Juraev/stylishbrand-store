<?php

namespace soft\helpers;

use Yii;

/**
 * Class SiteHelper
 * Saytda ishlatish ba'zi funksiyalar
 * @package soft\components
 */
class SiteHelper
{

    /**
     * Language param on query string
     */
    const LANGUAGE_PARAM = 'lang';

    /**
     * @return array
     */
    public static function languages()
    {
        return Yii::$app->params['languages'];
    }

    /**
     * Sets the language for the application
     */
    public static function setLanguage()
    {
        $params = Yii::$app->params;

        $lang = Yii::$app->request->get($params['languageParam'], $params['defaultLanguage']);

        $mobileLang = Yii::$app->request->headers->get('Accept-Language');

        if ($mobileLang) {
            $lang = $mobileLang;
        }
        Yii::$app->language = $lang;

        if (!array_key_exists($lang, $params['languages'])) {
            $lang = $params['defaultLanguage'];
        }
        Yii::$app->language = $lang;
    }

    /**
     * Sistemani aniqlash
     * @return string
     */
    public static function getOsName()
    {
        return strtoupper(substr(PHP_OS, 0, 3));
    }

    /**
     * @return string
     */
    public static function userDefaultAvatar()
    {
        return "/images/user-default-avatar.png";
    }

    /**
     * @return string
     */
    public static function faviconUrl()
    {
//        return Yii::$app->acf->getValue('site_favicon');
    }

    /**
     * @return string
     */
    public static function favicon()
    {
        return '<link rel="shortcut icon" href="' . self::faviconUrl() . '" type="image/jpg">';
    }

    /**
     * @return string|null
     */
    public static function siteTitle()
    {
//        return Yii::$app->acf->getValue('company_name');
    }

    /**
     * @return string|null
     */
    public static function siteDescription()
    {
//        return Yii::$app->acf->getValue('company_name');
    }

    /**
     * @return string|null
     */
    public static function siteKeywords()
    {
//        return strtr(Yii::$app->acf->getValue('company_name'), [' ' => ',']);
    }

    /**
     * @return string|null
     */
    public static function siteLogo()
    {
//        return Yii::$app->acf->getValue('site_logo');
    }

    /**
     * @return string
     */
    public static function imzo()
    {
        $lang = Yii::$app->language;
        if ($lang == 'uz') {
            $text = "{link} tomonidan ishlab-chiqilgan";
        } else if ($lang == 'ru') {
            $text = "Разработано {link}";
        } else {
            $text = "Developed by {link}";
        }

        $link = Html::a('iSoft', 'https://isoftware.uz', ['target' => '_blank', 'style' => ['color' => '#007bff;']]);
        return strtr($text, ['{link}' => $link]);
    }


    /**
     * @return string WIN|LIN
     */
    public static function osName()
    {
        return strtoupper(substr(PHP_OS, 0, 3));
    }


    /**
     * @param $type string
     * @param $message string
     */
    public static function flashMessage($type, $message)
    {

        if (is_console_app()) {
            echo $message . PHP_EOL;
        } else {
            Yii::$app->session->setFlash($type, $message);
        }

    }

    /**
     * @param $message string
     */
    public static function flashError($message)
    {
        self::flashMessage('error', $message);
    }

    /**
     * @param $message string
     */
    public static function flashSuccess($message)
    {
        self::flashMessage('success', $message);
    }

}


?>
