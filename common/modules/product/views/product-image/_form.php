<?php

use common\modules\product\models\Product;
use common\modules\product\models\ProductColor;
use soft\helpers\Html;
use soft\widget\kartik\ActiveForm;
use soft\widget\kartik\file\SingleImageFileInput;
use soft\widget\kartik\Form;

/* @var $this soft\web\View */
/* @var $model common\modules\product\models\ProductImage */

?>


<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'color_id:dropdownList' => [
            'items' => ProductColor::map(),
            'options' => [
                'prompt' => 'Tanlang...'
            ]
        ],
//        'product_id:dropdownList' => [
//            'items' => Product::map(),
//            'options' => [
//                'prompt' => 'Tanlang...'
//            ]
//        ],
        'image:widget' => [
            'widgetClass' => SingleImageFileInput::class,

            'options' => [
                'initialPreviewUrl' => $model->imageUrl
            ]
        ],
        'status:status'
    ]
]); ?>
<div class="form-group">
    <?= Html::submitButton(Yii::t('site', 'Save'), ['visible' => !$this->isAjax]) ?>
</div>

<?php ActiveForm::end(); ?>

