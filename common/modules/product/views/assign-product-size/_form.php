<?php

use soft\helpers\Html;
use soft\widget\kartik\Form;
use soft\widget\kartik\ActiveForm;
use common\modules\product\models\ProductSize;

/* @var $this soft\web\View */
/* @var $model common\modules\product\models\AssignProductSize */

?>


<?php $form = ActiveForm::begin(); ?>

<?= Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'size_id:select2' => [
            'options' => [
                'data' => ProductSize::map()
            ]
        ],
        'status:status'
    ]
]); ?>
<div class="form-group">
    <?= Html::submitButton(Yii::t('site', 'Save'), ['visible' => !$this->isAjax]) ?>
</div>

<?php ActiveForm::end(); ?>

