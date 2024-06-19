<?php

namespace soft\base;

use soft\helpers\ArrayHelper;
use yii\base\Model;

/**
 *
 * @property-read null|string $firstErrorMessage
 */
class SoftModel extends Model
{

    /**
     * @return string the first error text of the model after validating
     * */
    public function getFirstErrorMessage()
    {
        $firstErrors = $this->firstErrors;
        if (empty($firstErrors)) {
            return null;
        }
        $array = array_values($firstErrors);
        return ArrayHelper::getArrayValue($array, 0, null);
    }

}
