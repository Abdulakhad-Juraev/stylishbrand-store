<?php

use soft\helpers\Html;
use soft\widget\kartik\ActiveForm;
use soft\widget\kartik\file\SingleImageFileInput;
use soft\widget\kartik\Form;

/* @var $this soft\web\View */
/* @var $model common\modules\video\models\VideoAdditionalOption */

?>


<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
//        'video_id',
        'name_uz',
        'name_ru',
        'description_uz:textarea',
        'description_ru:textarea',
        'image:widget' => [
            'widgetClass' => SingleImageFileInput::class,
            'options' => [
                'initialPreviewUrl' => $model->getImageUrl(),
            ]
        ],
        'status:status',
//        'created_by',
//        'updated_by',
    ]
]); ?>
<div class="form-group">
    <?= Html::submitButton(Yii::t('site', 'Save'), ['visible' => !$this->isAjax]) ?>
</div>

<?php ActiveForm::end(); ?>

