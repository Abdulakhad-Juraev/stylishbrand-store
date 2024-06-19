<?php

use common\modules\order\traits\PaymentTypeTrait;
use common\modules\tariff\models\Tariff;
use soft\helpers\Html;
use soft\widget\kartik\ActiveForm;
use soft\widget\kartik\Form;

/* @var $this soft\web\View */
/* @var $model common\modules\userBalance\models\UserTariff */

?>


<?php $form = ActiveForm::begin(); ?>

<?= Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
//        'user_id',
        'tariff_id:select2' => [
            'options' => [
                'data' => Tariff::mapGroup()
            ]
        ],
        'payment_type_id:dropdownList' => [
            'items' => PaymentTypeTrait::types(),
        ],
//        'price',
//        'started_at',
//        'expired_at',
//        'order_id',
//        'status',
//        'created_by',
//        'updated_by',
    ]
]); ?>
<div class="form-group">
    <?= Html::submitButton(Yii::t('site', 'Save'), ['visible' => !$this->isAjax]) ?>
</div>

<?php ActiveForm::end(); ?>

