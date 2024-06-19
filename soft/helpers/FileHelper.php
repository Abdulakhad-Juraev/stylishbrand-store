<?php

namespace soft\helpers;

use Yii;

class FileHelper extends \yii\helpers\FileHelper
{

    const TYPE_WORD = 'word';
    const TYPE_EXCEL = 'excel';
    const TYPE_POWER_POINT = 'power_point';
    const TYPE_PDF = 'pdf';
    const TYPE_IMAGE = 'image';
    const TYPE_ARCHIVED = 'archive';
    const TYPE_VIDEO = 'video';

    const TYPES_LIST = [

        'doc' => self::TYPE_WORD,
        'docx' => self::TYPE_WORD,

        'xls' => self::TYPE_EXCEL,
        'xlsx' => self::TYPE_EXCEL,

        'ppt' => self::TYPE_POWER_POINT,
        'pptx' => self::TYPE_POWER_POINT,

        'pdf' => self::TYPE_PDF,

        'zip' => self::TYPE_ARCHIVED,
        'rar' => self::TYPE_ARCHIVED,

        'jpg' => self::TYPE_IMAGE,
        'jpeg' => self::TYPE_IMAGE,
        'bmp' => self::TYPE_IMAGE,
        'gif' => self::TYPE_IMAGE,
        'png' => self::TYPE_IMAGE,

        'mp4' => self::TYPE_VIDEO,
        'avi' => self::TYPE_VIDEO,
        'mpg' => self::TYPE_VIDEO,
        'mpeg' => self::TYPE_VIDEO,
        'wmv' => self::TYPE_VIDEO,

    ];

    /**
     * @param $extension string File extension
     * @param null|string $unknownFileType return value if file type does not find in [[self::TYPES_LIST]]
     * @return mixed
     */
    public static function getFileTypeByExtension($extension, $unknownFileType = null)
    {
        return ArrayHelper::getArrayValue(self::TYPES_LIST, $extension, $unknownFileType);
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public static function generateRandomName($length = 0)
    {
        $string = md5(Yii::$app->security->generateRandomString(10) . uniqid());
        if ($length > 0) {
            $string = substr($string, 0, $length);
        }
        return $string;
    }


}
