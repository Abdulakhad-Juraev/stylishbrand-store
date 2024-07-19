<?php

use kartik\color\ColorInput;
use soft\helpers\Html;
use soft\widget\kartik\ActiveForm;
use soft\widget\kartik\Form;

/* @var $this soft\web\View */
/* @var $model common\modules\product\models\ProductColor */

?>


<?php $form = ActiveForm::begin(); ?>

<?= Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'name',
        'color' => [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => ColorInput::class,
            'options' => [
                'options' => [
                    'class' => 'input_class'
                ],
            ],
        ],
        'status:status',

    ]
]);
?>
<div class="form-group">
    <?= Html::submitButton(Yii::t('site', 'Save'), ['visible' => !$this->isAjax]) ?>
</div>

<?php ActiveForm::end(); ?>

