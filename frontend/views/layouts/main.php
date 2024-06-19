<?php

use frontend\assets\AppAsset;
use soft\helpers\SiteHelper;
use soft\helpers\Url;
use soft\helpers\Html;
use soft\web\View;
use yii\bootstrap4\Alert;

/* @var $this View */
/* @var $content string */

AppAsset::register($this);

?>

<?php $this->beginPage() ?>

    <!DOCTYPE html>

    <html lang="<?= Yii::$app->language ?>" class="h-100">

    <head>

        <meta charset="<?= Yii::$app->charset ?>">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <?php $this->registerCsrfMetaTags() ?>

        <title><?= Html::encode($this->title) ?></title>

        <?= SiteHelper::favicon() ?>

        <?php $this->head() ?>

    </head>

    <body>
    <?php $this->beginBody() ?>

    <?= $content ?>

    <?php $this->endBody() ?>
    </body>
    </html>

<?php $this->endPage();
