<?php

namespace common\modules\translation\models\sync;


/**
 * Class MessageLocal
 * @property int $id [int(11)]
 * @property string $language [varchar(16)]
 * @property string $translation
 * @property int $created_at [int(11)]
 * @property int $updated_at [int(11)]
 */
class MessageLocal extends \soft\db\ActiveRecord
{
    public static function tableName()
    {
        return 'message';
    }

 


}
