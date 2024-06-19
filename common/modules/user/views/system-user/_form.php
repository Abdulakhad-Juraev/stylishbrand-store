<?php

use common\modules\user\models\AuthItem;
use common\modules\user\models\User;
use soft\helpers\Html;
use soft\widget\bs4\Card;
use soft\widget\input\VisiblePasswordInput;
use soft\widget\kartik\ActiveForm;
use soft\widget\kartik\file\SingleImageFileInput;
use soft\widget\kartik\Form;

/* @var $this soft\web\View */
/* @var $model common\modules\user\models\User */
/* @var $form ActiveForm */

$passwordHint = '';
if (!$model->isNewRecord) {
    $passwordHint = "Parolni o'zgartirish uchun bu yerga yangi parolni yozing. Ushbu maydonni bo'sh qoldirsangiz, parol o'zgarmaydi!";
}
$roles = AuthItem::userRoles();
?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="row">
    <div class="col-md-6">
        <?= Form::widget([
            'model' => $model,
            'form' => $form,
            'attributes' => [
                'username',
                'firstname',
                'lastname',
                'password:widget' => [
                    'widgetClass' => VisiblePasswordInput::class,
                    'hint' => $passwordHint
                ],
                'img:widget' => [
                    'widgetClass' => SingleImageFileInput::class,
                    'options' => [
                        'initialPreviewUrl' => $model->getImage(),
                    ]
                ],
                'status:radioButtonGroup' => [
                    'items' => User::formStatuses(),
                ],
            ]
        ]); ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('site', 'Save'), ['visible' => !$this->isAjax]) ?>
            <?= a('Bekor qilish', ['index'], ['class' => 'btn btn-warning']) ?>
        </div>
    </div>
    <div class="col-md-6">

        <?php Card::begin() ?>

        <h3>Foydalanuvchi roli</h3>

        <br>

        <?php foreach ($roles as $role): ?>

            <?= Html::checkbox('RoleName[]', $model->can($role->name), [
                'label' => $role->name,
                'value' => $role->name,
            ]) ?>

            <br>
        <?php endforeach; ?>

        <?php Card::end() ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
