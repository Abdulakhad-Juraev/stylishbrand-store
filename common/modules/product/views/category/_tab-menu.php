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
            'label' => 'Category haqida',
            'url' => ['/product-manager/category/view', 'id' => $model->id],
            'icon' => 'question-circle,far',
        ],
        [
            'label' => 'Sub Category',
            'url' => ['/product-manager/category/sub-category', 'id' => $model->id],
            'icon' => 'tasks,fas',
        ],
    ]
]) ?>
