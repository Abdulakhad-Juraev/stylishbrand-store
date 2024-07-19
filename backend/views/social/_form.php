<?php

use soft\helpers\Html;
use soft\widget\kartik\ActiveForm;
use soft\widget\kartik\file\SingleImageFileInput;
use soft\widget\kartik\Form;
use soft\widget\kartik\FormGrid;

/* @var $this soft\web\View */
/* @var $model common\models\Social */

?>


<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'url',
        'status:status',
    ]
]); ?>

<?= FormGrid::widget([
    'model' => $model,
    'form' => $form,
    'rows' => [
        [
            'attributes' => [
                'image:widget' => [
                    'widgetClass' => SingleImageFileInput::class,
                    'options' => [
                        'initialPreviewUrl' => $model->getImageUrl(),
                    ]
                ],
            ]
        ],
    ],
]); ?>
<div class="form-group">
    <?= Html::submitButton(Yii::t('site', 'Save'), ['visible' => !$this->isAjax]) ?>
</div>

<?php ActiveForm::end(); ?>

