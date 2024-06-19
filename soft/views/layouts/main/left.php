<?php

use soft\widget\adminlte2\Menu;

/** @var \soft\web\View $this */
/** @var array $menu */

$user = Yii::$app->user->identity;
$fullname = $user->fullname ?? '';
?>

<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $user->getAvatar() ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= $fullname ?></p>
            </div>
        </div>

        <?php if (can(section())): ?>
            <?= Menu::widget([
                'items' => $menu
            ]) ?>
        <?php endif; ?>

    </section>
</aside>
