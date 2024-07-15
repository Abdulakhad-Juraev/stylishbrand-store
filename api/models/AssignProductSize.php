<?php

namespace api\models;

use soft\helpers\Url;
use soft\db\ActiveQuery;

class AssignProductSize extends \common\modules\product\models\AssignProductSize
{
    /**
     * @return array|string[]
     */
    public function fields()
    {
        $key = self::generateFieldParam();

        if (isset(self::$fields[$key])) {
            return self::$fields[$key];
        }

        return [
            'id',
        ];
    }
}