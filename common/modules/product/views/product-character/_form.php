<?php

use common\modules\product\models\CategoryCharacter;
use common\modules\product\models\Product;
use soft\helpers\Html;
use soft\widget\kartik\ActiveForm;
use soft\widget\kartik\Form;

/* @var $this soft\web\View */
/* @var $model common\modules\product\models\ProductCharacter */

?>


<?php $form = ActiveForm::begin(); ?>

<?= Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'title',
        'category_character_id:dropdownList' => [
            'items' => CategoryCharacter::map(),
            'options' => [
                'prompt' => 'Tanlang...',
            ],
        ],
        'status:status',
        'with_check_icon:status',
    ]
]); ?>
<div class="form-group">
    <?= Html::submitButton(Yii::t('site', 'Save'), ['visible' => !$this->isAjax]) ?>
</div>

<?php ActiveForm::end(); ?>

