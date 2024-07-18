<?php

namespace ban;

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