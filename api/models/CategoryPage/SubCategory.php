<?php

namespace api\models\CategoryPage;
class SubCategory extends \common\modules\product\models\SubCategory
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
            'name',
        ];


    }


}