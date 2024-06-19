<?php

use soft\helpers\Html;

/* @var $this \yii\web\View */
/** @var \common\models\User|null $user */

$user = Yii::$app->user->identity;
$fullname = $user->fullname ?? '';

?>

<li class="dropdown user user-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <img src="<?= $user->getAvatar() ?>" class="user-image" alt="User Image">
        <span class="hidden-xs"><?= $fullname ?></span>
    </a>
    <ul class="dropdown-menu">
        <!-- User image -->
        <li class="user-header">
            <img src="<?= $user->getAvatar() ?>" class="img-circle" alt="User Image">

            <p>
                <?= $fullname ?>
                <small><?= date('Y-m-d H:i:s') ?></small>
            </p>
        </li>
        <!-- Menu Body -->
        <li class="user-body" style="display: none">
            <div class="row">
                <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                </div>
                <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                </div>
                <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                </div>
            </div>
            <!-- /.row -->
        </li>
        <!-- Menu Footer-->
        <li class="user-footer">
            <div class="pull-left">
                <a href="<?= to(['/profile-manager']) ?>" class="btn btn-default btn-flat"><i class="fa fa-user-cog"></i> Шахсий кабинет</a>
            </div>
            <div class="pull-right">
                <?= Html::beginForm(['/site/logout']) ?>
                <button type="submit" class="btn btn-default btn-flat"><i class="fas fa-sign-out-alt"></i> Chiqish</button>
                <?= Html::endForm() ?>
            </div>
        </li>
    </ul>
</li>
