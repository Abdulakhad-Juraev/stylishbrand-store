<?php

use common\modules\order\models\Order;
use common\modules\user\models\User;
use soft\helpers\Html;
use soft\widget\kartik\ActiveForm;
use soft\widget\kartik\Form;
use yii\widgets\MaskedInput;

/* @var $this soft\web\View */
/* @var $model common\modules\order\models\Order */

?>


<?php $form = ActiveForm::begin(); ?>

<?= Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
//        'user_id:dropdownList' => [
//            'items' => User::map(),
//            'options' => [
//                'prompt' => 'Tanlang...'
//            ]
//        ],
    'fullname',
        'phone' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => MaskedInput::class,
            'options' => [
                'mask' => '+999-99-999-99-99', // Adjust the mask as needed
            ],
            'label' => Yii::t('app', 'Phone'),
        ],
        'order_type:dropdownList' => [
            'items' => Order::orderTypes(),
            'options' => [
                'prompt' => 'Tanlang...'
            ]
        ],
        'payment_type:dropdownList' => [
            'items' => Order::types(),
            'options' => [
                'prompt' => 'Tanlang...'
            ]
        ],
        'total_price',
        'status:status',
    ]
]); ?>
<div class="form-group">
    <?= Html::submitButton(Yii::t('site', 'Save'), ['visible' => !$this->isAjax]) ?>
</div>

<?php ActiveForm::end(); ?>

