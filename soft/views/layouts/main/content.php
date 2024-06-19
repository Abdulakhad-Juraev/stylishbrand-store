<?php

use soft\helpers\Html;
use yii\widgets\Breadcrumbs;

/** @var string $content */
/** @var \soft\web\View $this */
?>

<div class="content-wrapper">
    <section class="content-header">
        <?php if (isset($this->blocks['content-header'])) { ?>
            <h1><?= $this->blocks['content-header'] ?></h1>
        <?php } else { ?>
            <h1>
                <?php
                if ($this->title !== null) {
                    echo \yii\helpers\Html::encode($this->title);
                } else {
                    echo \yii\helpers\Inflector::camel2words(
                        \yii\helpers\Inflector::id2camel($this->context->module->id)
                    );
                    echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                } ?>
            </h1>
        <?php } ?>

        <?php if ($this->headerText): ?>
            <p class="text-muted"><?= $this->headerText ?></p>
        <?php endif ?>

        <?= Breadcrumbs::widget([
            'links' => $this->params['breadcrumbs'] ?? []
        ]) ?>

    </section>
    <section class="content">
        <?= \common\widgets\Alert::widget() ?>
        <?= $content ?>
    </section>
</div>

<div class='control-sidebar-bg'></div>
