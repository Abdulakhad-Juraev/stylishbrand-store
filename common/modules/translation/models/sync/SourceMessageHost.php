<?php

namespace common\modules\translation\models\sync;

use Yii;

/**
 * Class SourceMessageHost
 * @property int $id [int(11)]
 * @property string $category [varchar(255)]
 * @property string $message
 * @property int $created_at [int(11)]
 * @property-read mixed $messages
 * @property int $updated_at [int(11)]
 */
class SourceMessageHost extends \soft\db\ActiveRecord
{
    public static function tableName()
    {
        return 'source_message';
    }

    public static function getDb()
    {
        return Yii::$app->db2;
    }

    public function getMessages()
    {
        return $this->hasMany(MessageHost::className(), ['id' => 'id']);
    }




}
