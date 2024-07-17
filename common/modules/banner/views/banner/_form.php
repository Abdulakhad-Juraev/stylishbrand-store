<?php

use common\modules\banner\models\Banner;
use common\modules\product\models\Category;
use soft\helpers\Html;
use soft\widget\kartik\ActiveForm;
use soft\widget\kartik\file\SingleImageFileInput;
use soft\widget\kartik\Form;

/* @var $this soft\web\View */
/* @var $model common\modules\banner\models\Banner */

?>


<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'title',
        'description',
        'image:widget' => [
            'widgetClass' => SingleImageFileInput::class,

            'options' => [
                'initialPreviewUrl' => $model->imageUrl
            ]
        ],
        'count:number',
        'button_url:dropdownList' => [
            'items' => Category::map(),
            'options' => [
            'prompt' => 'Tanlang...',
            ],
        ],
        'type:dropdownList' => [
            'items' => Banner::types(),
            'options' => [
                'prompt' => 'Tanlang...'
            ]
        ],
        'status:status',

    ]
]); ?>
<div class="form-group">
    <?= Html::submitButton(Yii::t('site', 'Save'), ['visible' => !$this->isAjax]) ?>
</div>

<?php ActiveForm::end(); ?>

