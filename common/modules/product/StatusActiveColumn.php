<?php

namespace common\modules\product;

use soft\helpers\Html;

class StatusActiveColumn
{

    /**
     * Faol
     */
    const STATUS_ACTIVE = 1;
    const USER_STATUS_ACTIVE = 10;

    /**
     * @param $model
     * @return string
     */

    public static function getStatuses($model, $controller): string
    {
        return ($model->getStatusAttributeValue() == self::STATUS_ACTIVE) ?
            Html::a('<i class="fa fa-toggle-on"></i>', [$controller . '/' . 'change', 'id' => $model->id])
            :
            Html::a('<i class="fa fa-toggle-off" style="color: red"></i>', [$controller . '/' . 'change', 'id' => $model->id]);
    }

    public static function getUserStatuses($model, $controller): string
    {
        return ($model->status == self::USER_STATUS_ACTIVE) ?
            Html::a('<i class="fa fa-toggle-on"></i>', [$controller . '/' . 'change', 'id' => $model->id])
            :
            Html::a('<i class="fa fa-toggle-off" style="color: red"></i>', [$controller . '/' . 'change', 'id' => $model->id]);
    }

}
