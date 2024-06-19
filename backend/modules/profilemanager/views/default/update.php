<?php

use kartik\form\ActiveForm;
use soft\widget\adminlte3\Card;
use yii\helpers\Html;


/* @var $this \yii\web\View */
/* @var $model \backend\modules\profilemanager\models\ChangePasswordForm */

$this->title = "Shaxsiy ma'lumotlarni o'zgartirish";
$this->params['breadcrumbs'][] = ['url' => ['index'], 'label' => 'Shaxsiy kabinet'];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-md-6">
        <?php Card::begin() ?>
        <h3 align="center"><?= $this->title ?></h3>
        <?php $form = ActiveForm::begin() ?>
        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'firstname') ?>
        <?= $form->field($model, 'lastname') ?>
        <?= Html::submitButton('Saqlash', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Bekor qilish', ['index'], ['class' => 'btn btn-warning']) ?>
        <?php ActiveForm::end() ?>
        <?php Card::end() ?>
    </div>
</div>

