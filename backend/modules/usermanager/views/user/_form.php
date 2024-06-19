<?php

use backend\modules\usermanager\models\User;

//use kartik\builder\Form;
use common\models\Category;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\bootstrap4\ActiveForm;

//use kartik\password\PasswordInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

/* @var $this soft\web\View */
/* @var $model backend\modules\usermanager\models\User */
/* @var $form ActiveForm */



$passwordHint = '';
if (!$model->isNewRecord) {
    $passwordHint = "Parolni o'zgartirish uchun bu yerga yangi parolni yozing. Ushbu maydonni bo'sh qoldirsangiz, parol o'zgarmaydi!";
}

?>
<div class="card card-primary">
    <div class="card-body">
        <?php $form = ActiveForm::begin() ?>

        <div class="row">
            <div class="col-6">
                <?= $form->field($model, 'firstname')->textInput(['autofocus' => true]) ?>

            </div>
            <div class="col-6">
                <?= $form->field($model, 'lastname')->textInput() ?>

            </div>

        </div>
        <div class="row">
            <div class="col-6">
                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'password')->textInput(); ?>
                <div class="hint-block">
                    <?= $passwordHint ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <?= $form->field($model, 'type_id')->dropdownList([
                    User::TYPE_ID_ADMIN => 'Admin',
                    User::TYPE_ID_EDITOR => 'Muharrir',
                    User::TYPE_ID_DIRECTOR => 'Direktor',
                ]) ?>


            </div>
            <div class="col-6">
                <?=$form->field($model,'status')->dropdownList(User::statuies())?>
            </div>
            <div class="col-6">
                <br>
                <div class="form-group" style="margin-top: 5px">
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Bekor qilish', ['index'], ['class' => 'btn btn-warning']) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end() ?>
    </div>
</div>
