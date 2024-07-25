<?php


/* @var $this soft\web\View */
/* @var $model common\modules\product\models\Product */

use soft\widget\bs4\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>


<?= $this->render('_tab-menu', ['model' => $model]) ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
              'id', 
              'price:sum',
            [
                'attribute' => 'country_id',
                'value' => function ($model) {
                    return $model->country->name ?? '';
                }
            ],
            [
                'attribute' => 'category_id',
                'value' => function ($model) {
                    return $model->category->name ?? '';
                }
            ],
            [
                'attribute' => 'sub_category_id',
                'value' => function ($model) {
                    return $model->subCategory->name ?? '';
                }
            ],
              'percentage',
              'published_at:datetime',
              'expired_at:datetime',
              'statusBadge:raw',
              'is_stock:bool',
            'most_popular:bool',
     ],
    ]) ?>
