<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;

?>

<div class="error-page">
    <h2 class="headline text-yellow"> <?= Yii::$app->response->statusCode  ?></h2>

    <div class="error-content">
        <h1>
            <i class="fa fa-warning text-yellow"></i>
            <?= Html::encode($this->title) ?>
        </h1>

        <h3>
            <?= nl2br(Html::encode($message)) ?>
        </h3>

    </div>
    <!-- /.error-content -->
</div>
