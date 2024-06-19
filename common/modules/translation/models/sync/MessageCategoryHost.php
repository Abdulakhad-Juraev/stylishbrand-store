<?php

/**
 * Class MessageCategoryHost
 * @property int $id [int(11)]
 * @property string $name [varchar(255)]
 * @property int $exported_at [int(11)]
 * @property int $imported_at [int(11)]
 */
class MessageCategoryHost extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'message_category';
    }

    public static function getDb()
    {
        return Yii::$app->db2;
    }

}