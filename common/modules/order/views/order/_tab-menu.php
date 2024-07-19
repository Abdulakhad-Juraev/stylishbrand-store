<?php


use soft\web\View;
use soft\widget\bs4\TabMenu;
use common\modules\product\models\Category;

/* @var $this View */
/* @var $model Category */

?>


<?= TabMenu::widget([

    'items' => [

        [
            'label' => 'Buyurtma haqida',
            'url' => ['/order-manager/order/view', 'id' => $model->id],
            'icon' => 'question-circle,far',
        ],
        [
            'label' => 'Buyurtma Ma\'lumotlari',
            'url' => ['/order-manager/order/order-item', 'id' => $model->id],
            'icon' => 'tasks,fas',
        ],
    ]
]) ?>
