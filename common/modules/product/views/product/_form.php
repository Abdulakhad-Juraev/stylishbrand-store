<?php

use common\modules\product\models\Category;
use common\modules\product\models\SubCategory;
use soft\helpers\Html;
use soft\widget\kartik\ActiveForm;
use soft\widget\kartik\Form;

/* @var $this soft\web\View */
/* @var $model common\modules\product\models\Product */

?>
<?php

if ($model->isNewRecord || !$model->published_at) {
//    $model->published_at = date('Y-m-d H:i');
//    $model->expired_at = date('Y-m-d H:i');
}
if (is_integer($model->published_at)) {
//    $model->published_at = date('Y-m-d H:i', $model->published_at);
//    $model->expired_at = date('Y-m-d H:i', $model->expired_at);
}

?>

<?php $form = ActiveForm::begin(); ?>

<?= Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'name',
        'price',
        'description',
        'category_id:dropdownList' => [
            'items' => Category::map(),
            'options' => [
                'prompt' => 'Tanlang...'
            ]
        ],
        'sub_category_id:dropdownList' => [
            'items' => SubCategory::map(),
            'options' => [
                'prompt' => 'Tanlang...'
            ]
        ],
        'percentage',
        'published_at:datetime',
        'expired_at:datetime',
        'status:status',
    ]
]); ?>
<div class="form-group">
    <?= Html::submitButton(Yii::t('site', 'Save'), ['visible' => !$this->isAjax]) ?>
</div>

<?php ActiveForm::end(); ?>

