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
            'label' => 'Mahsulot haqida',
            'url' => ['/product-manager/product/view', 'id' => $model->id],
            'icon' => 'question-circle,far',
        ],
        [
            'label' => 'Razmerlar',
            'url' => ['/product-manager/product/product-size', 'id' => $model->id],
            'icon' => 'tasks,fas',
        ],
        [
            'label' => 'Rasm va Rang',
            'url' => ['/product-manager/product/product-image', 'id' => $model->id],
            'icon' => 'tasks,fas',
        ],
        [
            'label' => 'Qo\'shimcha malumotlar',
            'url' => ['/product-manager/product/character', 'id' => $model->id],
            'icon' => 'tasks,fas',
        ],
    ]
]) ?>
