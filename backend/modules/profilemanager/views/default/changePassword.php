<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
use soft\widget\input\VisiblePasswordInput;

/* @var $this \yii\web\View */
/* @var $model \backend\modules\profilemanager\models\ChangePasswordForm */

$this->title = "Parolni o'zgartirish";
$this->params['breadcrumbs'][] = ['url' => ['index'], 'label' => 'Shaxsiy kabinet'];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-md-6">
        <h1 align="center"><?= $this->title ?></h1>
        <?php $form = ActiveForm::begin() ?>
        <?= $form->field($model, 'password')->widget(VisiblePasswordInput::class) ?>
        <?= $form->field($model, 'repassword')->widget(VisiblePasswordInput::class) ?>
        <?= Html::submitButton('Saqlash' , ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Bekor qilish' ,['index'], ['class' => 'btn btn-warning']) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>
