<?php

use common\models\User;
use common\modules\userBalance\models\PurchaseViaAdmin;
use soft\helpers\Html;
use soft\widget\kartik\ActiveForm;
use soft\widget\kartik\Form;

/* @var $this soft\web\View */
/* @var $model common\modules\userBalance\models\PurchaseViaAdmin */

?>


<?php $form = ActiveForm::begin(); ?>

<?= Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'status:checkbox' => [
            'options' => [
                'label' => "O'qib chiqildi"
            ]
        ],


    ]
]); ?>
<div class="form-group">
    <?= Html::submitButton(Yii::t('site', 'Save'), ['visible' => !$this->isAjax]) ?>
</div>

<?php ActiveForm::end(); ?>

