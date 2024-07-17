<?php

use soft\helpers\Html;
use soft\widget\kartik\Form;
use soft\widget\kartik\ActiveForm;
use soft\widget\kartik\file\SingleImageFileInput;

/* @var $this soft\web\View */
/* @var $model common\modules\product\models\Category */
?>


<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'name',
        'image:widget' => [
            'widgetClass' => SingleImageFileInput::class,

            'options' => [
                'initialPreviewUrl' => $model->imageUrl
            ]
        ],
        'status:status',
        'home_page:checkbox',
    ]
]); ?>
<div class="form-group">
    <?= Html::submitButton(Yii::t('site', 'Save'), ['visible' => !$this->isAjax]) ?>
</div>

<?php ActiveForm::end(); ?>

