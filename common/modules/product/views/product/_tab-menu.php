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
            'label' => 'Product haqida',
            'url' => ['/product-manager/product/view', 'id' => $model->id],
            'icon' => 'question-circle,far',
        ],
        [
            'label' => 'Product Size',
            'url' => ['/product-manager/product/product-size', 'id' => $model->id],
            'icon' => 'tasks,fas',
        ],
    ]
]) ?>
