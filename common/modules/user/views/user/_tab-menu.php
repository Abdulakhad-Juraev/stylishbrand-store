<?php
/*
 *  @author Shukurullo Odilov <shukurullo0321@gmail.com>
 *  @link telegram: https://t.me/yii2_dasturchi
 *  @date 11.05.2022, 11:26
 */

use common\modules\user\models\User;
use soft\widget\bs4\TabMenu;

/** @var User $model */

?>

<?= TabMenu::widget([

    'items' => [
        [
            'label' => " Um. ma'lumotlar",
            'url' => ['user/view', 'id' => $model->id],
            'icon' => 'info-circle'
        ],
        [
            'label' => " Qurilmalar",
            'url' => ['user/devices', 'id' => $model->id],
            'icon' => 'fas fa-laptop'
        ],
        [
            'label' => " Obunalar",
            'url' => ['user/tariffs', 'id' => $model->id],
            'icon' => 'list',
        ],
        [
            'label' => " To'lovlari",
            'url' => ['user/payments', 'id' => $model->id],
            'icon' => 'coins'
        ],
        [
            'label' => " Foydalanilgan Promo Kodlar",
            'url' => ['user/promo-code', 'id' => $model->id],
            'icon' => 'barcode'
        ],
        [
            'label' => " A'zolik kurslar",
            'url' => ['user/enroll', 'id' => $model->id],
            'icon' => 'list'
        ],
        [
            'label' => " Kitob buyurtmalar",
            'url' => ['user/order-book', 'id' => $model->id],
            'icon' => 'book'
        ],
    ]

]) ?>
