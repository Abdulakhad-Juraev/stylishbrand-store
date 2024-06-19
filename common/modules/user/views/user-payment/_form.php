<?php

use common\modules\user\models\User;
use common\modules\user\models\UserPayment;
use common\modules\video\models\Video;
use soft\helpers\Html;
use soft\widget\kartik\ActiveForm;
use soft\widget\kartik\Form;

/* @var $this soft\web\View */
/* @var $model common\modules\user\models\UserPayment */

?>


<?php $form = ActiveForm::begin(); ?>

<?= Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'user_id:select2' => [
            'options' => [
                'data' => User::map(),
            ]
        ],
        'amount:number',
        'payment_type_id:dropdownList' => [
            'items' => UserPayment::types(),
        ],

        'type_id:select2' => [
            'options' => [
                'data' => UserPayment::tariffCourseBooktypes(),
            ]
        ],
    ]
]); ?>
<div class="form-group">
    <?= Html::submitButton(Yii::t('site', 'Save'), ['visible' => !$this->isAjax]) ?>
</div>

<?php ActiveForm::end(); ?>

