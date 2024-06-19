<?php

use common\models\User;
use soft\helpers\Html;
use soft\widget\kartik\ActiveForm;
use soft\widget\kartik\Form;

/* @var $this soft\web\View */
/* @var $model common\modules\video\models\VideoComment */

?>


<?php $form = ActiveForm::begin(); ?>

<?= Form::widget([
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'user_id:select2' => [
                'options' => [
                        'data'  => User::map()
                ]
        ],
//        'video_id',
        'comment:textarea',
        'status:status',
//        'created_by',
//        'updated_by',
    ]
]); ?>
<div class="form-group">
    <?= Html::submitButton(Yii::t('site', 'Save'), ['visible' => !$this->isAjax]) ?>
</div>

<?php ActiveForm::end(); ?>

