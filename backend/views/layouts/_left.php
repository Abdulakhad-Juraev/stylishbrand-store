<?php

use soft\widget\adminlte3\Menu;
use yii\helpers\Url;


$menuItems = [
    [
        'label' => "Dashboard",
        'url' => ['/site/index'],
        'icon' => 'home',
    ],
    [
        'label' => "Foydalanuvchilar",
        'url' => ['/user-manager/user/index'],
        'icon' => 'users',
    ],

    [
        'label' => "Category",
        'url' => ['/product-manager/category/index'],
        'icon' => 'list',
    ],
    [
        'label' => "SubCategory",
        'url' => ['/product-manager/sub-category/index'],
        'icon' => 'list',
    ],
    [
        'label' => "Product",
        'url' => ['/product-manager/product/index'],
        'icon' => 'box-open',
    ],

    [
        'label' => "Sozlamalar",
        'icon' => 'cogs',
        'items' => [
            ['label' => 'Tarjimalar', 'url' => ['/translation-manager/default/index'], 'icon' => 'language'],
        ]
    ],
    [
        'label' => 'Keshni tozalash', 'url' => ['/site/cache-flush'], 'icon' => 'broom',

    ],
];

?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= to(['/site/index']) ?>" class="brand-link">
        <img src="<?= Url::base() ?>/template/win_user_1.png" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><?= Yii::$app->name ?></span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <?= Menu::widget([
                'items' => $menuItems,
            ]) ?>
        </nav>
    </div>
</aside>
