<?php

use common\models\IncomingMessage;
use common\models\SiteSettings;
use kartik\form\ActiveForm;
use soft\web\View;
use yii\bootstrap4\Html;

/* @var $this View */
/** @var IncomingMessage $model */
/** @var SiteSettings $settings */

$this->title = t("contact_us");
$this->addBreadCrumb($this->title);

$settings = SiteSettings::find()
    ->orderBy(['id' => SORT_DESC])
    ->one();
?>

<div class="main-page-w">
    <div class="my-container">
        <div class="main-in">
            <div class="main contact-page">
                <iframe class="con-map"
                        src="<?= $settings ? $settings->location : '' ?>"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                <div class="footer-in-form ">
                    <h1 class="txt-20">
                        Xabar yozish
                    </h1>
                    <?php $form = ActiveForm::begin([
                        'options' => [
                            'class' => 'form-footer'
                        ]
                    ]) ?>
                    <label for="in1" class="label txt-16">
                        <?= t('Your firstname') ?>
                    </label>
                    <?= $form->field($model, 'full_name')->textInput(['class' => 'input txt-16', 'id' => 'in1'])->label(false) ?>
                    <label for="in2" class="label txt-16">
                        <?= t('Your email') ?>
                    </label>
                    <?= $form->field($model, 'email')->textInput(['class' => 'input txt-16', 'id' => 'in2'])->label(false) ?>
                    <label for="in4" class="label txt-16">
                        <?= t('Phone number') ?>
                    </label>
                    <?= $form->field($model, 'phone')->textInput(['class' => 'input txt-16', 'id' => 'in4'])->label(false) ?>
                    <label for="in3" class="label txt-16">
                        <?= t('Your message') ?>
                    </label>
                    <?= $form->field($model, 'message')->textarea(['class' => 'input txt-16', 'id' => 'in3'])->label(false) ?>
                    <?= Html::submitButton(t('Send'), ['class' => 'glavni-btn txt-18']) ?>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
</div>