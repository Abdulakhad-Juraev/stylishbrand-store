<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator soft\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use soft\helpers\Html;
use soft\widget\kartik\ActiveForm;
use soft\widget\kartik\Form;

/* @var $this soft\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

?>


    <?= "<?php " ?>$form = ActiveForm::begin(); ?>

    <?= "<?= " ?>Form::widget([
        'model' => $model,
        'form' => $form,
        'attributes' => [
    <?php
    foreach ($generator->getColumnNames() as $attribute) {
        if (in_array($attribute, $safeAttributes) && !in_array($attribute, ['created_at', 'updated_at'] ) ) {
            echo "              '" . $attribute . "',\n";
        }
    }
    ?>
        ]
    ]); ?>
    <div class="form-group">
        <?= "<?= " ?>Html::submitButton(Yii::t('site', 'Save'), ['visible' => !$this->isAjax ] ) ?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

