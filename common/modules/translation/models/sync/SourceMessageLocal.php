<?php

namespace common\modules\translation\models\sync;

/**
 * Class SourceMessageLocal
 * @property int $id [int(11)]
 * @property string $category [varchar(255)]
 * @property string $message
 * @property int $created_at [int(11)]
 * @property-read MessageLocal $messages
 * @property int $updated_at [int(11)]
 */
class SourceMessageLocal extends \soft\db\ActiveRecord
{
    public static function tableName()
    {
        return 'source_message';
    }

    public function getMessages()
    {
        return $this->hasMany(MessageLocal::className(), ['id' => 'id']);
    }

    public static function newSourceMessagesOnLocal()
    {
        $lastExportedTime = MessageCategoryLocal::lastExportedTime();
        return  static::find()->andWhere(['>=', 'created_at', $lastExportedTime])->all();
    }

}
