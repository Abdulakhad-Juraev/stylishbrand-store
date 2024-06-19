<?php

use common\modules\video\models\Video;
use soft\helpers\Html;
use soft\widget\kartik\ActiveForm;
use soft\widget\kartik\file\SingleImageFileInput;
use soft\widget\kartik\Form;

/* @var $this soft\web\View */
/* @var $model common\modules\video\models\Homework */

?>


<?php $form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
    ]
]); ?>

<?= Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'title',

    ]
]); ?>
<label for="">File</label>
<?= $form->field($model, 'file_url',['options' => ['class' => 'form-control']])->fileInput()->label('') ?>
<br>
<?= Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'status:status',
    ]
]); ?>
<div class="form-group">
    <?= Html::submitButton(Yii::t('site', 'Save'), ['visible' => !$this->isAjax]) ?>
</div>

<?php ActiveForm::end(); ?>

