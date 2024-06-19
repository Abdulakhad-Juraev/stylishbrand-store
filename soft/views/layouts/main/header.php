<?php

/* @var $this \yii\web\View */

use common\modules\settings\models\FifoLifo;
use soft\helpers\SiteHelper;

?>

<header class="main-header">
    <a href="#" class="logo">
        <span class="logo-mini"><b>PEG</b></span>
        <span class="logo-lg"><b>Pegasus</b> MCHJ</span>
    </a>
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <i class="fas fa-bars"></i>
        </a>
        <a href="<?= Yii::$app->homeUrl ?>" class="sidebar-toggle" style="font-family: inherit">
            <i class="fas fa-landmark"></i> <?= Yii::$app->name ?>
        </a>

        <?php if (isSection(['admin', 'finance', 'store'])): ?>
            <a href="#" class="sidebar-toggle" style="font-family: inherit">
                <i class="fas fa-arrow-up"></i> Расход тизими:
                <label class="label label-success" style="font-size: 13px"><?= FifoLifo::activeName() ?></label>
            </a>
        <?php endif ?>

        <a href="#" class="sidebar-toggle" style="font-family: inherit">
            <i class="fas fa-dollar-sign"></i> Доллар курси:
            <label class="label label-primary"
                   style="font-size: 13px"><?= as_integer(SiteHelper::dollarValue()) ?></label>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <?= $this->render('_userAccount') ?>
            </ul>
        </div>
    </nav>
</header>
