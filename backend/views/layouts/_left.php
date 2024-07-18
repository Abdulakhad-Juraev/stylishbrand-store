<?php

use yii\helpers\Url;
use soft\widget\adminlte3\Menu;


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
        'label' => "Banner",
        'url' => ['/banner-manager/banner/index'],
        'icon' => 'window-restore',
    ],
    [
        'label' => "Kategoriyalar",
        'url' => ['/product-manager/category/index'],
        'icon' => 'list',
    ],
    [
        'label' => "Maxsulotlar",
        'url' => ['/product-manager/product/index'],
        'icon' => 'tshirt',
    ],
    [
        'label' => "Buyurtmalar",
        'url' => ['/order-manager/order/index'],
        'icon' => 'cart-arrow-down',
    ],




    [
        'label' => "Qo'shimcha Ma'lumotlar",
        'icon' => 'stream',
        'items' => [

            [
                'label' => "O'lchamlar",
                'url' => ['/product-manager/product-size/index'],
                'icon' => 'ruler-combined',
            ],
            [
                'label' => "Ranglar",
                'url' => ['/product-manager/product-color/index'],
                'icon' => 'palette',
            ],
            [
                'label' => "Brendlar",
                'url' => ['/product-manager/brand/index'],
                'icon' => 'copyright',
            ],
            [
                'label' => "Davlatlar",
                'url' => ['/product-manager/country/index'],
                'icon' => 'globe',
            ],
            [
                'label' => 'Ijtimoiy tarmoq', 'url' => ['/social/index'], 'icon' => 'share-alt',
            ],
            ]
    ],
    [
        'label' => "Sozlamalar",
        'icon' => 'cogs',
        'items' => [
            ['label' => 'Tarjimalar', 'url' => ['/translation-manager/default/index'], 'icon' => 'language'],
        ]
    ],
    [
        'label' => t('clear_cache'), 'url' => ['/site/cache-flush'], 'icon' => 'broom',

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
