<?php

namespace api\modules\profile\models;


use Yii;


class UserPayment extends \common\modules\user\models\UserPayment
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
            'amount',
            'amountFormat' => function (UserPayment $model) {
                return as_sum($model->amount);
            },
            'user_id',
            'userFullName' => function (UserPayment $model) {
                return $model->user->fullname;
            }
            ,
            'payment_type_id',
            'paymentTypeName' => function (UserPayment $model) {
                return $this->getTypeName();
            },
            'type_id',
            'typeName' => function (UserPayment $model) {
                return $this->tariffCourseBooktypes() ? $this->getTariffCourseBookTypeName() : '';
            },
            'created_at' => function (UserPayment $model) {
                return Yii::$app->formatter->asDatetime($this->created_at, 'php:d.m.Y H:i');
            },
//            'update_at' => function (UserPayment $model) {
//                return Yii::$app->formatter->asDatetime($this->updated_at, 'php:d.m.Y H:i');
//            }
        ];

    }
}